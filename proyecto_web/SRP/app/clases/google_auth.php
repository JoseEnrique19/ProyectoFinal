<?php
	include_once('db.php');

	class GoogleAuth{

		protected $client;

		public function __construct(Google_Client $googleClient = null){
			$this->client=$googleClient;
			if ($this->client){
				$this->client->setClientID('170922212979-p0sblcbh9tij6bnjv5sgqho7cd13l3gi.apps.googleusercontent.com');
				$this->client->setClientSecret('i6NE9sX-m1a1NsE6CGvLEcFC');
				$this->client->setredirectUri('http://localhost/SRP/index.php');			
				$this->client->setScopes('email');
			}
		}

		public function isLoggedIn(){
			return isset($_SESSION['acces_token']);
		}

		public function getAuthUrl(){
			return $this->client->createAuthUrl();
		}

		public function checkRedirectCode(){
			if (isset($_GET['code'])) {
				$this->client->authenticate($_GET['code']);
				$this->setToken($this->client->getAccessToken());

				$payload = $this->getPayload();

				$this->createUser($payload);
				//echo "<pre>".print_r($payload)."</pre>";
				return true;
			}
			return false;
		}

		public function setToken($token){
			$_SESSION['acces_token'] = $token;
			$this->client->setAccessToken($token);
		}

		public function logout(){
			unset($_SESSION['acces_token']);
		}

		public function getPayload(){
			$payload = $this->client->verifyIdToken();
			return $payload;
		}

		public function createUser(){
			$db = new DB();
			$conn = $db->get_connection();

			try{
				$payload = $this->getPayload();
				$query= "insert into users (google_id,email) values (?,?)";
				$statement = $conn->prepare($query);

				$a=$payload['sub'];
				$b=$payload['email'];

				echo "<h1>".$a." ".$b."</h1>";

				$statement->bind_param("ss", $a,$b);

				//$statement->bind_param("ss", $payload['payload']['sub'], $payload['payload']['email']);
				$statement->execute();
			}catch(Exception $ex){

			}finally{
				$statement->close();
				$conn->close();
			}
		}
	}
?>