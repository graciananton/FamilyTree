import mysql.connector

class DatabaseManager():  
    def __init__(self):
        self.connect()

    def connect(self):
        self.connection = mysql.connector.connect(
            host = "localhost",
            user = 'root',
            password = '',
            database = "familytree"
        )
        self.cursor = self.connection.cursor()
        
    def getSetting(self,name_value):
        self.cursor.execute("SELECT * FROM setting WHERE name = %s",(name_value,))
        row = self.cursor.fetchall()
        self.close()
        return row


    def close(self):
        self.cursor.close()
        self.connection.close()

