import os
import sys
from admin.py_classes.Config import Config

if sys.argv[2] != "localhost":
    sys.path.insert(0, Config.PythonSitePackages())




#from typing_extensions import TypedDict, Annotated
from typing import TypedDict, Annotated

from langchain_google_genai import ChatGoogleGenerativeAI

from langchain_core.prompts import ChatPromptTemplate

from langchain_community.utilities import SQLDatabase
from langchain_community.tools.sql_database.tool import QuerySQLDatabaseTool
from dotenv import load_dotenv
from pathlib import Path
#if sys.argv[2] == 'localhost':
#    db = SQLDatabase.from_uri("mysql+mysqlconnector://root:@localhost:3306/familytree")
#else:
#    db = SQLDatabase.from_uri("mysql+mysqlconnector://dbu5691915:FamilyTree123%23@db5017690433.hosting-data.io/dbs14144770")

db = SQLDatabase.from_uri(Config.MySQLConnectionUrl(sys.argv[2]))

load_dotenv(Path(".env"))
os.getenv("GOOGLE_API_KEY")

llm = ChatGoogleGenerativeAI(model="gemini-2.5-flash")

class State(TypedDict):
    question: str # the question in natural language
    query: str # the sql query from the natural language
    result: str # the result when the sql query is run
    answer: str # formats the sql query in a natural language format

system_message = """
Given an input question, create a syntactically correct {dialect} query to
run to help find the answer. Unless the user specifies in his question a
specific number of examples they wish to obtain, always limit your query to
at most {top_k} results. You can order the results by a relevant column to
return the most interesting examples in the database.

Never query for all the columns from a specific table, only ask for the
few relevant columns given the question.

Pay attention to use only the column names that you can see in the schema
description. Be careful to not query for columns that do not exist. Also,
pay attention to which column is in which table.

Only use the following tables:
{table_info}
"""

user_prompt = "Question: {input}"


query_prompt_template = ChatPromptTemplate(
    [("system", system_message), ("user", user_prompt)]
)

class QueryOutput(TypedDict):
    query: Annotated[str, ..., "Syntactically valid SQL query."]

def write_query(state: State):
    prompt = query_prompt_template.invoke({
        "dialect": "mysql",
        "top_k": 10,
        "table_info": db.get_table_info(["person", "relation"]),
        "input": state["question"],
    })
    structured_llm = llm.with_structured_output(QueryOutput)
    result = structured_llm.invoke(prompt)
    return {"query": result["query"]}

def execute_query(state: State):
    execute_query_tool = QuerySQLDatabaseTool(db=db)
    return {"result": execute_query_tool.invoke(state["query"])}

def generate_answer(state: State):
    prompt = (
        "Given the following user question, corresponding SQL query, "
        "and SQL result, answer the user question\n\n"
        f"Question: {state['question']}\n"
        f"SQL Query: {state['query']}\n"
        f"SQL Result: {state['result']}"
    )
    response = llm.invoke(prompt)
    return {"answer": response.content}

# --- STEP 9: RUN EVERYTHING

state: State = {
    "question": sys.argv[1],
    "query": "",
    "result": "",
    "answer": ""
}

# Generate query
query_result = write_query(state)
state["query"] = query_result["query"]

# Run query
execution_result = execute_query(state)
state["result"] = execution_result["result"]

# Get final answer
answer_result = generate_answer(state)
state["answer"] = answer_result["answer"]

with open("log.txt",'w',encoding='utf-8') as file:
    file.write(state['answer'])
    
print(state['answer'])




