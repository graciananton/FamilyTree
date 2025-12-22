
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
from dotenv import load_dotenv
from pathlib import Path
from langchain_google_genai import ChatGoogleGenerativeAI


type = sys.argv[1]
selectedPerson = sys.argv[2]

db = DatabaseService()

persons = db.executeQuery(type,"persons",selectedPerson)
relations = db.executeQuery(type,"relations",selectedPerson)

db = SQLDatabase.from_uri(Config.DB_CONNECTION)


load_dotenv(Path(".env"))
os.getenv("GOOGLE_API_KEY")

llm = ChatGoogleGenerativeAI(model="gemini-2.5-flash")

prompt = ChatPromptTemplate.from_messages([
    ("system",
    "You are an expert genealogical AI that generates structured biographies.\n\n"
    "⚠️ Output Rules:\n"
    "- Output must ALWAYS be valid JSON in this format:\n"
    "[{{\"pid\": \"...\", \"AIBiography\": \"...\"}}, ...]\n"
    "- Output ONLY JSON. Do not add explanations, comments, or extra text.\n"
    "- Do not invent any people who are not in the input data.\n"
    "- All biographies must include as much relational detail as possible.\n"
    "- Use full sentences for biographies (third person).\n"
    "- Wrap every relative name in an <a> tag like this:\n"
    "- Put information in the present tense.\n"
    "<a href='?pid={{pid}}&pageType=page_profile&display_type=horizontal&req=searchForm#result'>{{firstName}} {{lastName}}</a>\n\n"
    "📖 Relationship Mapping Rules:\n"
    "- Parents: From relation.fpid and relation.mid.\n"
    "- Spouse: From relation.psid.\n"
    "- Children: Any person X is a child of Y if relation.fpid=Y.pid or relation.mid=Y.pid.\n"
    "- Grandchildren: Any person X is a child of one of this person's children.\n"
    "- Grandparents: Parents of this person's parents (fpid/mid of the father/mother).\n\n"
    "- Siblings: children of the fpid(or mid) of the person X"
    "📝 Biography Content:\n"
    "- Always start with the person's full name, gender (M/F), and birth/death dates if present.\n"
    "- Include spouse(s).\n"
    "- List parents (if any).\n"
    "- List children (if any).\n"
    "- List grandchildren (if any).\n"
    "- List grandparents (if any).\n\n"
    " - List siblings (if any).\n\n"
    "Do not leave placeholders like 'Unknown' — omit the section if no relatives exist."
    ),
    ("human",
     "Persons:\n{persons}\n\n"
     "Relations:\n{relations}\n\n"
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
