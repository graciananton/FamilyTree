with open('pythonLog.txt','w') as file:
    file.write("beginning of execution\n")


import sys


with open('pythonLog.txt','a') as file:
    file.write("\n".join(sys.path)+"\n")


# ✅ 1. Tell Python where your external modules are
sys.path.insert(0, "/kunden/homepages/3/d1017242952/htdocs/familyTree/python_modules")

# ✅ 2. Tell Python where to find your own package: py_classes/
sys.path.insert(0, "/kunden/homepages/3/d1017242952/htdocs/familyTree")

                                                              
with open('pythonLog.txt','a') as file:
    file.write("installing everything")

import os

"""
dir_path = "/kunden/homepages/3/d1017242952/htdocs/familyTree/python_modules"

if os.path.exists(dir_path):
    with open("pythonLog.txt",'a') as file:
        file.write("Directory exists for python_modules")
else:
    with open("pythonLog.txt",'a') as file:
        file.write("Directory DNE for python_modules")

"""

with open("pythonLog.txt",'a') as file:
    file.write("\n".join(os.listdir("/kunden/homepages/3/d1017242952/htdocs/familyTree/python_modules"))+"\n")



import ast
import json
import warnings
import re

warnings.filterwarnings("ignore")

with open('pythonLog.txt','a') as file:
        file.write("after importing")

try:
    from pydantic import BaseModel
    with open('pythonLog.txt', 'a') as file:
        file.write("✅ Successfully imported pydantic\n")
        
    from langchain_community.utilities.sql_database import SQLDatabase
    from langchain.chat_models import init_chat_model
    from langchain_core.prompts import PromptTemplate
    from langchain_core.runnables import RunnableSequence

    import py_classes.classes as classes
    import py_classes.config as config

    with open('pythonLog.txt','a') as file:
        file.write("imported successfully")

except  ImportError as e:

    with open('pythonLog.txt','a') as file:
        file.write("error in importing module " + str(e)+"\n")


if __name__ == "__main__":

    config.setup_api_key()


    llm = init_chat_model("gemini-2.0-flash", model_provider="google_genai")

    #servername = "localhost"
    #username   = "root"
    #password   = ""
    #dbname     = "familytree"

    servername = "db5017690433.hosting-data.io"
    username = "dbu5691915"
    password = "FamilyTree123#"
    dbname = "dbs14144770"


    uri = f"mysql+mysqlconnector://{username}:{password}@{servername}/{dbname}"

    db = SQLDatabase.from_uri(
        uri,
        include_tables = ['person','relation']
    )
    

    table_description = classes.DatabaseManager()
    table_description = (table_description.getSetting("table_description"))[0][2]


    table_info = table_description + db.get_table_info()

    #question = sys.argv[1]

    question  = "Who is the father of Gracian"

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

