<?php 

    namespace edj\mvcframecore\db;

    use edj\mvcframecore\Application;
    use app\migrations\m0001_initial;
    


    class Database
    {
        public \PDO $pdo;

        public function __construct(array $config) {

            $dsn = $config['dsn'] ?? '';
            $user = $config['user'] ?? '';
            $password = $config['password'] ?? '';

            $this->pdo = new \PDO($dsn, $user, $password);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }

        //Apply Migrations
        public function applyMigrations()
        {
            $this->createMigrationsTable();
            $appliedMigrations = $this->getAppliedMigrations();

           
           $newMigration = [];
            $files = scandir(Application::$ROOT_DIR.'/migrations');
          
            $toApplyMigrations = array_diff($files, $appliedMigrations);
            
            
            
            foreach ($toApplyMigrations as $migration) {
                # code...
                if ($migration === '.' || $migration === '..') {
                    # code...
                    continue;
                }
                
              
               
                require_once Application::$ROOT_DIR. '/migrations/' .$migration;
                $className = pathinfo($migration, PATHINFO_FILENAME);
               
                $instance = new $className;
             
                $this->log("Applying migration $migration");
                $instance->up();
                $this->log("Applied migration $migration");
                $newMigration[] = $migration;
           
               
            }

            if (!empty($newMigration)) {
                # code...
                $this->saveMigration($newMigration);
            } else {
                $this->log("All Migrations are applied") ;
            }
         

              /*echo '<pre>';
                var_dump($className);
                echo '</pre>';
                exit;  */
        }

        //Create Migrations */
        public function createMigrationsTable()
        {
            # code...
            $this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations ( 
                id INT AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=INNODB;");
        }

        // Get Applied Migrations
        public function getAppliedMigrations()
        {
           $statement= $this->pdo->prepare("SELECT migration FROM migrations");
           $statement->execute();

           return $statement->fetchAll(\PDO::FETCH_COLUMN);
        }

        //Save Migrations

        public function saveMigration(array $migrations)
        {   
           
            $str = implode("," ,  array_map(fn($m)=> "('$m')", $migrations));
               
           $statement = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES $str ");
           $statement->execute();

        } 

        protected function log($message)
        {
            echo '['.date('d-m-Y H:i:s').'] - '.$message.PHP_EOL;
        }

        public function prepare($sql)
        {
            return $this->pdo->prepare($sql);
        }
    }











?>