<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\CardsResource;
use App\Http\Requests\API\CreditRequest;
use Dingo\Api\Routing\Helpers;
use App\Models\Customer;
use App\Models\CustomerCard;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CreditCardController extends Controller
{
    use Helpers;
    
    /**
     * @OA\Get(
     *      path="/user/credit-cards",
     *      operationId="credit_cards",
     *      tags={"credit_cards"},
     *      summary="Get list of all user cerdit cards",
     *      description="Returns list of credit cards",   
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *      )
     * )
     */
    public function index()
    {
        $user = User::find(auth()->user()->id);
        $customerId = $user->customerInfo->id;
        $cards = CustomerCard::where('customer_id', $customerId)->get();

        return response(['cards' => CardsResource::collection($cards), 'message' => 'Retrieved successfully'], 200);
    }

    /**
     * @OA\POST(
     *      path="/user/credit-cards/add",
     *      operationId="credit_cards_add",
     *      tags={"addCreditCard"},
     *      summary="Post add new credit card",
     *      description="add new credit card for user",
     *     @OA\Parameter(
     *          name="name",
     *          description="credit card name",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="number",
     *          description="credit card number",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="number"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="expire_month",
     *          description="credit card expire month date",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="number"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="expire_year",
     *          description="credit card expire year date",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="number"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="cvv",
     *          description="credit card cvv",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="number"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *      )
     * )
     */
    public function store(CreditRequest $request)
    {
        $customer = Customer::where('user_id', auth()->user()->id)->first();

        $data=$request->all();
        $card = $customer->createCard($data);

        $card_data = CustomerCard::latest('created_at')->first();

        return response(['card' => new CardsResource($card_data), 'message' =>'Created successfully'], 201);
    }

    /**
     * @OA\PUT(
     *      path="/user/credit-cards/update",
     *      operationId="credit_cards_update",
     *      tags={"updateCreditCard"},
     *      summary="Post edit credit card",
     *      description="edit credit card for user",
     *     @OA\Parameter(
     *          name="name",
     *          description="credit card name",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="number",
     *          description="credit card number",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="number"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="expire_month",
     *          description="credit card expire month date",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="number"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="expire_year",
     *          description="credit card expire year date",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="number"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="cvv",
     *          description="credit card cvv",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="number"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *      )
     * )
     */
    public function update($id, Request $request, CustomerCard $card)
    {
        $data = $card->findOrFail($id);
        $data->fill($request->all());
        if ($data->confirmed == 'true') {
            $data->confirmed = 1;
        } else {
            $data->confirmed = 0;
        }
        $data->push();
        $card_data = CustomerCard::latest('updated_at')->first();

        return response(['card' => new CardsResource($card_data), 'message' => 'Update successfully'], 200);
    }

    /**
     * @OA\POST(
     *      path="/user/credit-cards/delete",
     *      operationId="credit_cards_delete",
     *      tags={"deleteCreditCard"},
     *      summary="delete credit card",
     *      description="delete credit card info for user",
     *     @OA\Parameter(
     *          name="id",
     *          description="credit card id",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="number"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *      )
     * )
     */
    public function destroy($id, CustomerCard $card)
    {
        $card = CustomerCard::find($id);
        $card->transactions()->delete();
        $card->delete();
        return response(['message' => 'Deleted']);
    }
}



