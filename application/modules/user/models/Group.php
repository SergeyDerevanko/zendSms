<?php
class User_Model_Group  extends Ikantam_Model_Abstract{


    /* SET PUBLIC FUNCTION */

    public function create($data){
        $this->setName($data['name'])
            ->setSlug($data['slug'])
            ->save();
        return $this;
    }




}