from admin.py_classes.Config import Config
import mysql.connector

class DatabaseService:
    def __init__(self):
        self.conn = None
    def createConnection(self):
        self.conn = mysql.connector.connect(
            host=Config.DB_HOST,
            user=Config.DB_USER,
            password=Config.DB_PASSWORD,
            database=Config.DB_NAME
        )

    def getQuery(self,type,name):
        if type == 'allAiBios' and name == "persons":
            query = "SELECT pid,firstName,lastName,birthDate,gender,email,phoneNumber,address,deathDate FROM person"
        elif type == "allAiBios" and name == 'relations':
            query = "SELECT pid,fpid,mid,psid FROM relation"


        if type == "emptyAiBios" and name == "persons":
            query = """
            SELECT p.pid, p.firstName, p.lastName, p.birthDate, p.gender, 
                p.email, p.phoneNumber, p.address, p.deathDate, 
                r.fpid, r.mid, r.psid
            FROM person p
            INNER JOIN relation r ON r.pid = p.pid
            WHERE p.AIBiography = ''
            """
        elif type == "emptyAiBios" and name == "relations":
            query = """
            SELECT p.pid, p.firstName, p.lastName, p.birthDate, p.gender, 
                p.email, p.phoneNumber, p.address, p.deathDate, 
                r.fpid, r.mid, r.psid
            FROM person p
            INNER JOIN relation r ON r.pid = p.pid
            WHERE p.AIBiography = ''
            """


        if type == "eachAiBio" and name == "persons":
            query = """
                SELECT pid, firstName, lastName, birthDate, gender, email, phoneNumber, address, deathDate
                FROM person
                WHERE pid = %s
            """
        elif type == "eachAiBio" and name == "relations":
            query = """SELECT pid,fpid,mid,psid 
                       FROM relation 
                       WHERE pid = %s
                    """

        return query
    def executeQuery(self,type,name,selectedPerson):

        if self.conn == None:
            self.createConnection()

        query = self.getQuery(type,name)
        cursor = self.conn.cursor(dictionary=True)
        if type == "eachAiBio":
            cursor.execute(query,(selectedPerson,))
        else:
            cursor.execute(query)

        result = cursor.fetchall()
        cursor.close()
        return result

    def closeConnection(self):
        self.conn.close()
        self.conn = None