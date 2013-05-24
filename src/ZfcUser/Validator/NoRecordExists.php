<?php

namespace ZfcUser\Validator;

class NoRecordExists extends AbstractRecord
{
    /**
     * @var array Message templates
     */
    protected $messageTemplates = array(
        self::ERROR_NO_RECORD_FOUND => 'No user found with such data',
        self::ERROR_RECORD_FOUND    => 'User with such data already exists',
    );

    public function isValid($value)
    {
        $valid = true;
        $this->setValue($value);

        $result = $this->query($value);
        if ($result) {
            $valid = false;
            $this->error(self::ERROR_RECORD_FOUND);
        }

        return $valid;
    }
}
