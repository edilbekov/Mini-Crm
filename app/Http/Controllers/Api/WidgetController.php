<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\WidgetTicketRequest;
use App\Models\Customer;
use App\Models\Ticket;
use Illuminate\Http\JsonResponse;

class WidgetController extends Controller
{
    public function submit(WidgetTicketRequest $request): JsonResponse
    {
        try {
            // Find or create customer
            $customer = Customer::firstOrCreate(
                ['phone' => $request->phone],
                [
                    'name' => $request->name,
                    'email' => $request->email,
                ]
            );

            // Create ticket
            $ticket = Ticket::create([
                'customer_id' => $customer->id,
                'subject' => $request->subject,
                'description' => $request->description,
                'status' => Ticket::STATUS_NEW,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Ваша заявка принята. Мы свяжемся с вами в ближайшее время!',
                'ticket_id' => $ticket->id,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Произошла ошибка. Пожалуйста, попробуйте еще раз.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
