<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\db\Query;

/**
 * LoanRequest model
 *
 * @property int $id
 * @property int $user_id
 * @property int $amount
 * @property int $term
 * @property string $status
 * @property string $created_at
 * @property string $processed_at
 */
class LoanRequest extends ActiveRecord
{
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_DECLINED = 'declined';
    
    public function init()
    {
        parent::init();
        if ($this->isNewRecord) {
            $this->status = self::STATUS_PENDING;
        }
    }
    
    public static function tableName()
    {
        return 'loan_requests';
    }
    
    public function rules()
    {
        return [
            [['user_id', 'amount', 'term'], 'required'],
            [['user_id', 'amount', 'term'], 'integer', 'min' => 1],
            ['status', 'in', 'range' => [self::STATUS_PENDING, self::STATUS_APPROVED, self::STATUS_DECLINED]],
            ['user_id', 'validateUserHasNoApprovedLoan', 'on' => 'create'],
        ];
    }
    
    public function validateUserHasNoApprovedLoan($attribute, $params)
    {
        $existingApproved = (new Query())
            ->from(self::tableName())
            ->where(['user_id' => $this->user_id, 'status' => self::STATUS_APPROVED])
            ->exists();
            
        if ($existingApproved) {
            $this->addError($attribute, 'User already has an approved loan');
        }
    }
    
    public static function getPendingRequests()
    {
        return self::find()->where(['status' => self::STATUS_PENDING])->all();
    }
    
    public function approve()
    {
        $this->status = self::STATUS_APPROVED;
        $this->processed_at = date('Y-m-d H:i:s');
        return $this->save(false);
    }
    
    public function decline()
    {
        $this->status = self::STATUS_DECLINED;
        $this->processed_at = date('Y-m-d H:i:s');
        return $this->save(false);
    }
}
