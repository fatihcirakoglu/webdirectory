<?php
  require_once 'Database.php';

  class Message{
    private $db;

    public function __construct(){
      $this->db = new Database;
    }

    public function send($sender_id, $receiver_id, $message){
      $this->db->query('INSERT INTO messages(sender_id, receiver_id, message)
       VALUES(:sender_id, :receiver_id, :message)');

       //Bind the values
       $this->db->bind(':sender_id', $sender_id);
       $this->db->bind(':receiver_id', $receiver_id);
       $this->db->bind(':message', $message);

       //Execute
       return $this->db->execute();
    }

    public function delete_by_sender($mid){
      $this->db->query('UPDATE messages SET isDeletedBySender = :val WHERE mid = :mid');

      //Bind the value
      $this->db->bind(':mid', $mid);
      $this->db->bind(':val', 1);

      //Execute
      return $this->db->execute();
    }

    public function delete_by_receiver($mid){
      $this->db->query('UPDATE messages SET isDeletedByReceiver = :val WHERE mid = :mid');

      //Bind the value
      $this->db->bind(':mid', $mid);
      $this->db->bind(':val', 1);

      //Execute
      return $this->db->execute();
    }

    public function delete_conversation($current_user_id, $user_id){
      $this->db->query('UPDATE messages SET isDeletedByReceiver = :val , isRead = 1 WHERE
                        receiver_id = :user1 AND sender_id = :user2');

      //Bind the value
      $this->db->bind(':user1', $current_user_id);
      $this->db->bind(':user2', $user_id);
      $this->db->bind(':val', 1);

      //Execute
      $this->db->execute();
      
      
      $this->db->query('UPDATE messages SET isDeletedBySender = :val WHERE
                        receiver_id = :user2 AND sender_id = :user1');

      //Bind the value
      $this->db->bind(':user1', $current_user_id);
      $this->db->bind(':user2', $user_id);
      $this->db->bind(':val', 1);

      //Execute
      return $this->db->execute(); 
    }

    public function get_conversation($user1_id, $user2_id){

      $this->db->query('SELECT * FROM messages WHERE (sender_id = :user1 AND receiver_id = :user2 AND isDeletedBySender = 0) OR
                        (sender_id = :user2 AND receiver_id = :user1 AND isDeletedByReceiver = 0)');

      //Bind the value
      $this->db->bind(':user1', $user1_id);
      $this->db->bind(':user2', $user2_id);

      //Execute
      $rows = $this->db->resultSet();

      return $rows;
    }

    public function get_message($mid){
      $this->db->query('SELECT * FROM messages WHERE mid = :mid');

      //Bind the value
      $this->db->bind(':mid', $mid);

      //Execute
      $row = $this->db->single();

      return $row;
    }

    public function get_conversation_user_ids($current_user_id){


      $this->db->query('SELECT sender_id, receiver_id, time FROM messages
       WHERE receiver_id = :receiver_id OR sender_id = :sender_id
       AND isDeletedByReceiver = 0 ORDER BY time DESC');

      // Bind the value
      $this->db->bind(':receiver_id', $current_user_id);
      $this->db->bind(':sender_id', $current_user_id);

      //Execute
      $rows1 = $this->db->resultSet();
      
      

      $this->db->query('SELECT sender_id, receiver_id, time FROM messages
       WHERE receiver_id = :receiver_id OR sender_id = :sender_id
       AND isDeletedBySender = 0  ORDER BY time DESC');

      // Bind the value
      $this->db->bind(':receiver_id', $current_user_id);
      $this->db->bind(':sender_id', $current_user_id);


      //Execute
      $rows2 = $this->db->resultSet();


      //return all results
      $all_user_ids = array_merge($rows1, $rows2);
      $will_be_returned_ids = [];

      foreach($all_user_ids as $user_ids){
        if(!$this->isThereUserPair($user_ids->receiver_id, $user_ids->sender_id, $will_be_returned_ids)){
          $will_be_returned_ids[] = $user_ids;
        }
      }

      return $will_be_returned_ids;
    }

    public function get_last_message_in_conversation($user1_id, $user2_id){
      $this->db->query('SELECT * FROM messages WHERE (receiver_id = :user1 AND sender_id = :user2)
                       OR (receiver_id = :user2 AND sender_id = :user1) ORDER BY time DESC LIMIT 1');

      //Bind the value
      $this->db->bind(':user1', $user1_id);
      $this->db->bind(':user2', $user2_id);

      //Execute
      $row = $this->db->single();

      return $row;
    }

    private function isThereUserPair($user1_id, $user2_id, $user_pairs){
      foreach($user_pairs as $user_pair){
        if( ($user_pair->receiver_id == $user1_id AND $user_pair->sender_id == $user2_id) OR 
            ($user_pair->receiver_id == $user2_id AND $user_pair->sender_id == $user1_id)  ){
          return true;
        }
      }

      return false;
    }

    public function get_unread_message_number_in_conversation($sender_id, $receiver_id){
      $this->db->query('SELECT COUNT(*) AS msg_num FROM messages WHERE
       isRead = :val AND sender_id = :sender_id AND receiver_id = :receiver_id');

      //Bind the values
      $this->db->bind(':val', 0);
      $this->db->bind(':sender_id', $sender_id);
      $this->db->bind(':receiver_id', $receiver_id);

      //Execute
      return $this->db->single()->msg_num;
    }

    public function get_unread_message_number($receiver_id){
      $this->db->query('SELECT COUNT(*) AS msg_num FROM messages WHERE isRead = :val
      AND receiver_id = :receiver_id');

      //Bind the values
      $this->db->bind(':val', 0);
      $this->db->bind(':receiver_id', $receiver_id);

      //Execute
      return $this->db->single()->msg_num;
    }

    public function mark_all_of_them_as_read($uid){
      $this->db->query('UPDATE messages SET isRead = 1 WHERE uid = :uid AND isRead = 0');

      //Bind the value
      $this->db->bind(':uid', $uid);

      //Execute
      return $this->db->execute();
    }

    public function mark_message_as_read($mid){
      $this->db->query('UPDATE messages SET isRead = 1 WHERE mid = :mid AND isRead = 0');

      //Bind the value
      $this->db->bind(':mid', $mid);

      //Execute
      return $this->db->execute();
    }

    public function isThereUnreadMessageInConversation($receiver_id, $sender_id){

      $this->db->query('SELECT * FROM messages WHERE sender_id = :sender_id OR receiver_id = :receiver_id
                        AND isRead = 0');

      //Bind the value
      $this->db->bind(':sender_id', $sender_id);
      $this->db->bind(':receiver_id', $receiver_id);

      //Execute
      $this->db->execute();

      return $this->db->rowCount();
    }
    
    public function isThereUnDeletedMessageInConversation($user1, $user2){

      $this->db->query('SELECT * FROM messages WHERE (receiver_id = :user1 AND sender_id = :user2 AND isDeletedByReceiver = 0)
                        OR (receiver_id = :user2 AND sender_id = :user1 AND isDeletedBySender = 0)');

      //Bind the value
      $this->db->bind(':user1', $user1);
      $this->db->bind(':user2', $user2);

      //Execute
      $this->db->execute();

      return $this->db->rowCount();
    }

    public function isThereNewMessage($receiver_id){
      $unread_message_number = $this->get_unread_message_number($receiver_id);
      if($unread_message_number > 0){
        return true;
      }
      return false;
    }

  }
