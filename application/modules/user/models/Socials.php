<?php
class User_Model_Socials  extends Ikantam_Model_Abstract{

    /* SET PUBLIC FUNCTION */
    public function create($data){
        $this->setType($data['type'])
            ->setUserId($data['user_id'])
            ->setSocialId($data['social_id'])
            ->save();
        return $this;
    }

}