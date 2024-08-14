<?php

namespace App\Services;

use App\Models\PaymentMethods;

class PaymentMethodServices
{
    private $object;
    public function __construct(ObjectServices $objectService)
    {
        $this->object = $objectService;
    }

    public function get($id)
    {
        return PaymentMethods::where('ID', $id)->first();
    }
    public function getList()
    {
        $result = PaymentMethods::query()->select(['ID', 'DESCRIPTION'])->get();

        return $result;
    }

    public function getListNotIncludeOneParam(int $ID)
    {
        $result = PaymentMethods::query()->select(['ID', 'DESCRIPTION'])->where('ID', '<>', $ID)->get();

        return $result;
    }
    public function getListNonPatient()
    {
        $result = PaymentMethods::query()->select(['ID', 'DESCRIPTION'])->where('PAYMENT_TYPE', '<=', '8')->get();

        return $result;
    }
    public function Store(string $CODE, string $DESCRIPTION, int $PAYMENT_TYPE, int $GL_ACCOUNT_ID): int
    {
        $ID = $this->object->ObjectNextID('PAYMENT_METHOD');
        $OBJECT_TYPE = (int) $this->object->ObjectTypeID('PAYMENT_METHOD');
        PaymentMethods::create([
            'ID'            => $ID,
            'CODE'          => $CODE !== '' ? $CODE : $this->object->GetSequence($OBJECT_TYPE, null),
            'DESCRIPTION'   => $DESCRIPTION,
            'PAYMENT_TYPE'  => $PAYMENT_TYPE,
            'GL_ACCOUNT_ID' => $GL_ACCOUNT_ID > 0 ? $GL_ACCOUNT_ID : null,
        ]);
        return $ID;
    }

    public function Update(int $ID, string $CODE, string $DESCRIPTION, int $PAYMENT_TYPE, int $GL_ACCOUNT_ID): void
    {
        PaymentMethods::where('ID', $ID)->update([
            'CODE'          => $CODE,
            'DESCRIPTION'   => $DESCRIPTION,
            'PAYMENT_TYPE'  => $PAYMENT_TYPE,
            'GL_ACCOUNT_ID' => $GL_ACCOUNT_ID > 0 ? $GL_ACCOUNT_ID : null,
        ]);
    }

    public function Delete(int $ID): void
    {
        PaymentMethods::where('ID', $ID)->delete();
    }
    public function Search($search)
    {
        return PaymentMethods::query()
            ->select([
                'payment_method.ID',
                'payment_method.CODE',
                'payment_method.DESCRIPTION',
                'payment_method.PAYMENT_TYPE',
                'payment_method.GL_ACCOUNT_ID',
                't.DESCRIPTION as TYPE',
                'a.NAME as ACCOUNT'
            ])
            ->join('payment_type_map as t', 't.ID', '=', 'payment_method.PAYMENT_TYPE')
            ->leftJoin('account as a', 'a.ID', '=', 'payment_method.GL_ACCOUNT_ID')
            ->when($search, function ($query) use (&$search) {
                $query->where('payment_method.CODE', 'like', '%' . $search . '%')
                    ->orWhere('payment_method.DESCRIPTION', 'like', '%' . $search . '%');
            })
            ->orderBy('payment_method.ID', 'desc')
            ->get();
    }
}
