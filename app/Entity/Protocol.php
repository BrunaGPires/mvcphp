<?php 
namespace App\Entity;

use \App\Db\Database;
use PDO;

class Protocol
{
    public function __construct(
        public int $id,
        public string $description,
        public string $createdAt,
        public string $deadline,
        public User $user,
    ) {
    }
    
    public static function make(array $payload): self {
        $user = User::getUser($payload['user_id']);
        $createdAt = date('Y-m-d H:i:s');
        return new Protocol(
            id: $payload['id'] ?? 0,
            description: $payload['description'],
            createdAt: $createdAt,
            deadline: $payload['deadline'],
            user: $user
        );
    }

    public function cadastrar(){
        $this->createdAt = date('Y-m-d H:i:s');

        $obDatabase = new Database('protocols');
        $this->id = $obDatabase->insert([
            'description' => $this->description,
            'createdAt' => $this->createdAt,
            'deadline' => $this->deadline,
            'user_id' => $this->user->id
        ]);
        
        return true;
    }

    public function atualizar()
    {
        return (new Database('protocols'))->update('id = ' . $this->id, [
            'description' => $this->description,
            'deadline' => $this->deadline,
            'user_id' => $this->user->id
        ]);
    }

    public function excluir()
    {
        return (new Database('protocols'))->delete('id = ' . $this->id);
    }

    /**
     * obtem os protocolos do banco de dados
     */
    public static function getProtocols($where = null, $order = null, $limit = null)
    {
        return (new Database('protocols'))->select($where, $order, $limit)->fetchAll(PDO::FETCH_CLASS,self::class);
    }

    /**
     * obtem protocolo por id
     */
    public static function getProtocol($id)
    {
        return self::make((new Database('protocols'))->select('id = ' . $id)->fetch(PDO::FETCH_ASSOC));
    }
}
?>