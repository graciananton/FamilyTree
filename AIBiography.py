
import sys
import os
import re, json

from admin.py_classes.Config import Config

sys.path.insert(0, Config.SITE_PACKAGES_LOCATION)


from langchain.chat_models import init_chat_model
from langchain.prompts import ChatPromptTemplate
from langchain_core.prompts import ChatPromptTemplate
from langchain_community.utilities import SQLDatabase
import mysql.connector
from admin.py_classes.DatabaseService import DatabaseService


level = sys.argv[1]
type = sys.argv[2]
selectedPerson = sys.argv[3]

db = DatabaseService()


persons = db.executeQuery(type,"persons",selectedPerson)
relations = db.executeQuery(type,"relations",selectedPerson)

db = SQLDatabase.from_uri(Config.DB_CONNECTION)

os.environ["GOOGLE_API_KEY"] = Config.GEMINI_API_KEY

llm = init_chat_model("gemini-2.5-flash", model_provider="google_genai")

prompt = ChatPromptTemplate.from_messages([
    ("system",
     "Output must always be valid JSON in this format: "
    "[{{\"pid\": \"...\", \"AIBiography\": \"...\"}}, ...] "
    "- Do NOT invent any people. Only use entries that exist in {persons} and {relations}. "
    "Example output: "
    "[{{\"pid\": \"1151\", \"AIBiography\": \"Joycian Anton (M), born on October 15, 2010 (age 14). "
    "He is the son of <a href='?pid={{pid}}&pageType=page_profile&display_type=horizontal&req=searchForm#result'>Basil Anton</a> "
    "and <a href='?pid={{pid}}&pageType=page_profile&display_type=horizontal&req=searchForm#result'>Premala Anton</a>. "
    "His siblings are <a href='?pid={{pid}}&pageType=page_profile&display_type=horizontal&req=searchForm#result'>Gracia Anton</a>, "
    "<a href='?pid={{pid}}&pageType=page_profile&display_type=horizontal&req=searchForm#result'>Joycia Anton</a>, "
    "and <a href='?pid={{pid}}&pageType=page_profile&display_type=horizontal&req=searchForm#result'>Gracian Anton</a>. "
    "On his paternal side, his grandparents are <a href='?pid={{pid}}&pageType=page_profile&display_type=horizontal&req=searchForm#result'>Fernando Anton Anton</a> "
    "and <a href='?pid={{pid}}&pageType=page_profile&display_type=horizontal&req=searchForm#result'>Pushpum Anton</a>. "
    "On his maternal side, his grandparents are <a href='?pid={{pid}}&pageType=page_profile&display_type=horizontal&req=searchForm#result'>Santhiapillai Anton</a> "
    "and <a href='?pid={{pid}}&pageType=page_profile&display_type=horizontal&req=searchForm#result'>Mary Anton</a>.\"}}] "
    ),
    ("human",
     "Persons:\n{persons}"
     "Relations:\n{relations}"
     "Generate the biographies now.")
])

chain = prompt | llm | {"llm_response": lambda x: x.content}

biography = chain.invoke({
    "persons":persons,
    "relations":relations
})
raw_output = biography["llm_response"]

json_str = re.sub(r"^```(?:json|python)?\s*|\s*```$", "", raw_output.strip(), flags=re.DOTALL)

biographies = json.loads(json_str)

print(json.dumps(biographies, ensure_ascii=False))
