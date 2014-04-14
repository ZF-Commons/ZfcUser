<?php
namespace ZfcUser\Validator;

/**
 * Class RecordExists
 * @package ZfcUser\Validator
 */
class RecordExists extends AbstractRecord
{
    /**
     * @param mixed $value
     * @return bool
     */
    public function isValid($value)
    {
        $this->setValue($value);

        if (!$this->query($value)) {
            $this->error(self::ERROR_NO_RECORD_FOUND);
            return false;
        }

        return true;
    }
}
