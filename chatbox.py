from py_classes.imports import *
import os
from langchain_community.utilities.sql_database import SQLDatabase  # :contentReference[oaicite:1]{index=1}
from langchain_community.utilities import SQLDatabase
from langchain.chat_models import init_chat_model
from langchain_core.prompts import PromptTemplate
from langchain_core.runnables import RunnableSequence
import ast
import sys
import json
import warnings
warnings.filterwarnings("ignore")
import re

import py_classes.classes as classes
import py_classes.config as config

if __name__ == "__main__":

    
    config.setup_api_key()


    llm = init_chat_model("gemini-2.0-flash", model_provider="google_genai")

    servername = "localhost"
    username   = "root"
    password   = ""
    dbname     = "familytree"

    uri = f"mysql+mysqlconnector://{username}:{password}@{servername}/{dbname}"

    db = SQLDatabase.from_uri(
        uri,
        include_tables = ['person','relation']
    )


    table_description = classes.DatabaseManager()
    table_description = (table_description.getSetting("table_description"))[0][2]


    table_info = table_description + db.get_table_info()

    question = sys.argv[1]

    prompt = PromptTemplate.from_template(
    "Write a MySQL query to answer the question using only these tables:\n\n{table_info}\n\nQuestion: {question}"
    )

    chain = prompt | llm

    sql_response = chain.invoke({"question": question, "table_info": table_info})

    sql_query = sql_response.content

    match = re.findall(r"```(?:sql|mysql)\s*(.*?)```", sql_query, re.DOTALL | re.I)

    #runs the sql statement
    results = db.run(match[0])
    # makes the results into a list from a string
    results = ast.literal_eval(results)
    string = ""
    tracker = 0
    for result in results:
        tracker = tracker + 1
        if len(results) == 1:
            string += result[0]
        else:
            if tracker == len(results):
                string += result[0]
            else:
                string += result[0] + ", "

    print(json.dumps(string))

