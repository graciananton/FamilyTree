import os

def setup_api_key():
    if not os.environ.get("GOOGLE_API_KEY"):
        os.environ["GOOGLE_API_KEY"] = "AIzaSyA26EG1w4Ac-CAQautdio8h-D8iv7m4RqQ"
