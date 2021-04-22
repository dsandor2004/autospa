<?php
namespace Application\Entity;

class EditableDeletableEntity
{
	const EDITABLE_MINUTES = 45;
	
    public function isEditableAndDeletable()
    {
        $now = new \DateTime();
        $createdAt = $this->getCreatedAt();
        
        $now_time = strtotime($now->format('Y-m-d H:i:s'));
        $create_time = strtotime($createdAt->format('Y-m-d H:i:s'));
        
        if ($now_time - $create_time > self::EDITABLE_MINUTES * 60)
            return false;
        else
            return true;
    }    
}
?>
