<?php
namespace Judge\Database;

use PDO;
use PDOException;

class DB
{
    protected $host;
    protected $port;
    protected $dbname;
    protected $username;
    protected $password;
    protected $conn;

    /**
     * DB constructor. By default connect to papaki.gr DB (MySQL) and to the 'fab' database schema.
     */
    public function __construct()
    {
        $this->host = getenv('HOST');
        $this->port = getenv('PORT');
        $this->dbname = getenv('DBNAME');
        $this->username = getenv('USERNAME');
        $this->password = getenv('PASSWORD');

        $this->connect();
    }

    /**
     * Alternative DB constructor for connection to the Homestead virtual DB server
     * @param string $servername
     * @param string $port
     * @param string $dbname
     * @param string $username
     * @param string $password
     */
//    public function __construct($servername = "127.0.0.1", $port = "33060", $dbname = "fab", $username = "homestead", $password = "secret")
//    {
//        $this->servername = $servername;
//        $this->port = $port;
//        $this->dbname = $dbname;
//        $this->username = $username;
//        $this->password = $password;
//
//        $this->connect();
//    }

    public function connect()
    {
        try {
            $conn = new PDO("mysql:host=$this->host;port:$this->port;dbname=$this->host", $this->username, $this->password);
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn = $conn;
//            echo "Connected successfully";
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    public function getUser($email, $password)
    {
        $stmt = $this->conn->prepare("SELECT * FROM ashoka_coursework.users WHERE userEmail LIKE :userEmail AND userPassword LIKE :userPassword");
        $stmt->bindParam(':userEmail', $email);
        $stmt->bindParam(':userPassword', $password);
        $stmt->execute();

        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetch();

        return $result;
    }

    public function getAllMajors()
    {
        $stmt = $this->conn->prepare("SELECT * FROM ashoka_coursework.majors");
        $stmt->execute();

        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        return $result;
    }

    public function getAllProfessors()
    {
        $stmt = $this->conn->prepare("SELECT * FROM ashoka_coursework.professors");
        $stmt->execute();

        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        return $result;
    }

    public function passJudgment($data)
    {
        try {
            $stmt = $this->conn->prepare("INSERT INTO ashoka_coursework.judgments (`userID`, `professorID`, `yyyy`, `eloquent`, `knowledgable`, `politeAndRespectful`, `helpfulAccessibleAndCaring`, `preparedAndPunctual`, `inspiringAndEngaging`, `comment`)
    VALUES (:userID, :professorID, CURDATE(), :eloquent, :knowledgable, :politeAndRespectful, :helpfulAccessibleAndCaring, :preparedAndPunctual, :inspiringAndEngaging, :comment)");
            $stmt->bindParam(':userID', $_SESSION['userID']);
            $stmt->bindParam(':professorID', $data['professorID']);
            $stmt->bindParam(':eloquent', $data['eloquent']);
            $stmt->bindParam(':knowledgable', $data['knowledgeable']);
            $stmt->bindParam(':politeAndRespectful', $data['polite']);
            $stmt->bindParam(':helpfulAccessibleAndCaring', $data['helpful']);
            $stmt->bindParam(':preparedAndPunctual', $data['prepared']);
            $stmt->bindParam(':inspiringAndEngaging', $data['inspiring']);
            $stmt->bindParam(':comment', $data['comment']);

            $stmt->execute();
            $errorMessage = "";

        } catch (PDOException $e) {
            $errorMessage = $e->getMessage();
        }
        
        return $errorMessage;
    }

    public function getProfessorByID($id)
    {
        $stmt = $this->conn->prepare("
SELECT * FROM ashoka_coursework.professors
INNER JOIN ashoka_coursework.majors
ON professors.majorID=majors.majorID
WHERE professors.id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetch();

        return $result;
    }

    public function getProfessorByUrlName($urlName)
    {
        $stmt = $this->conn->prepare("
SELECT * FROM ashoka_coursework.professors
INNER JOIN ashoka_coursework.majors
ON professors.majorID=majors.majorID
WHERE professors.urlName LIKE :urlName");
        $stmt->bindParam(':urlName', $urlName);
        $stmt->execute();

        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetch();

        return $result;
    }

    public function getJudgmentsForProfessorByID($id)
    {
        $stmt = $this->conn->prepare("SELECT AVG(eloquent), AVG(knowledgable), AVG(politeAndRespectful), AVG(helpfulAccessibleAndCaring), AVG(preparedAndPunctual), AVG(inspiringAndEngaging) FROM ashoka_coursework.judgments WHERE professorID= :id ;");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetch();

        return $result;
    }

    public function getCommentsForProfessorByID($id)
    {
        $stmt = $this->conn->prepare("SELECT judgmentID,userID,comment FROM ashoka_coursework.judgments WHERE professorID=:id ;");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        return $result;
    }

    public function updateProfessorUrlByID($id, $urlName)
    {
        try {
            $stmt = $this->conn->prepare("UPDATE ashoka_coursework.professors SET urlName= :urlName WHERE id= :id");
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':urlName', $urlName);
            $stmt->execute();

            $result = $stmt->rowCount();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        
        return $result;
    }

    public function reportCommentByJudgmentID($id)
    {
        try {
            $stmt = $this->conn->prepare("UPDATE ashoka_coursework.judgments SET reported=reported+1 WHERE judgmentID = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $result = $stmt->rowCount();
        } catch (PDOException $e) {
            $result = $e->getMessage();
        }

        return $result;
    }

    public function unreportCommentByJudgmentID($id)
    {
        try {
            $stmt = $this->conn->prepare("UPDATE ashoka_coursework.judgments SET reported=reported-1 WHERE judgmentID = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $result = $stmt->rowCount();
        } catch (PDOException $e) {
            $result = $e->getMessage();
        }

        return $result;
    }

    public function findProfessorByName($theName)
    {
        $stmt = $this->conn->prepare("
SELECT * FROM ashoka_coursework.professors
INNER JOIN ashoka_coursework.majors
ON professors.majorID=majors.majorID
WHERE professors.name LIKE '%$theName%'");
        $stmt->execute();

        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        return $result;
    }

    public function getProfessorByName($theName)
    {
        $stmt = $this->conn->prepare("
SELECT * FROM ashoka_coursework.professors
INNER JOIN ashoka_coursework.majors
ON professors.majorID=majors.majorID
WHERE professors.name LIKE :theName");
        $stmt->bindParam(':theName', $theName);
        $stmt->execute();

        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetch();

        return $result;
    }

    public function getCountOfJudgments($profID)
    {
        $stmt = $this->conn->prepare("
SELECT Count(*) AS totalCount FROM ashoka_coursework.judgments
WHERE judgments.professorID = :id");
        $stmt->bindParam(':id', $profID);
        $stmt->execute();

        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetch();

        return $result;
    }

    public function getReportedComments()
    {
        $stmt = $this->conn->prepare("
SELECT * FROM ashoka_coursework.judgments
WHERE reported > 0");
        $stmt->execute();

        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        return $result;
    }

    public function deleteCommentByID($id)
    {
        try {
            $stmt = $this->conn->prepare("DELETE FROM ashoka_coursework.judgments WHERE judgmentID=:id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            // set the resulting array to associative
            $count = $stmt->rowCount();

            if ($count > 0) {
                $result['success'] = 0;
                $result['message'] = "Comments successfully deleted!";
            } else {
                $result['success'] = 1;
                $result['message'] = "No rows were deleted!";
            }
        } catch (PDOException $e) {
            $result['success'] = 1;
            $result['message'] = $e->getMessage();
        }

        return $result;
    }

    public function unreportCommentByID($id)
    {
        try {
            $stmt = $this->conn->prepare("UPDATE ashoka_coursework.judgments SET reported=0 WHERE judgmentID=:id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            // set the resulting array to associative
            $count = $stmt->rowCount();

            if ($count > 0) {
                $result['success'] = 0;
                $result['message'] = "Comments successfully updated!";
            } else {
                $result['success'] = 1;
                $result['message'] = "No rows were updated!";
            }
        } catch (PDOException $e) {
            $result['success'] = 1;
            $result['message'] = $e->getMessage();
        }

        return $result;
    }

}