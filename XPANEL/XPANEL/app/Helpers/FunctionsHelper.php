<?php

namespace App\Helpers;

use App\Helpers\Logs\LogTransfer;
use App\Helpers\Logs\LogWriter;
use App\Models\AccRegNum;
use App\Models\Char;
use App\Models\GameAccount;
use App\Models\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class FunctionsHelper
{
    public function getRealIP()
    {
        $realIP = $_SERVER['REMOTE_ADDR'];
        if (isset($_SERVER['HTTP_X_REAL_IP']))
            $realIP = $_SERVER['HTTP_X_REAL_IP'];
        elseif (isset($_SERVER['HTTP_CLIENT_IP']))
            $realIP = $_SERVER['HTTP_CLIENT_IP'];
        elseif (isset($_SERVER['HTTP_CF_CONNECTING_IP']))
            $realIP = $_SERVER['HTTP_CF_CONNECTING_IP'];

        return $realIP;
    }

    public function get_donapoints($master_id)
    {
        $user = User::where('id', '=', $master_id)->first();
        return $user->donation_points;
    }

    public function has_donapoints($master_id, $amount)
    {
        $dona_points = $this->get_donapoints($master_id);
        if ($dona_points >= $amount) {
            return true;
        } else {
            return false;
        }
    }

    private function getMaxMail()
    {
        $mail_id = DB::table('mail')->max('id');
        return ($mail_id) ? $mail_id : false;
    }

    public function less_donapoints($master_id, $amount)
    {
        $dona_points = $this->get_donapoints($master_id);
        $total = $dona_points - $amount;
        User::where('id', $master_id)->update(['donation_points' => $total]);
    }

    public function insertMailAttachment($nameid, $amount, $index)
    {
        DB::table('mail_attachments')->insert([
            'id' => $this->getMaxMail(),
            'index' => $index,
            'nameid' => $nameid,
            'amount' => $amount,
            'identify' => 1,
        ]);
    }

    public function sendItemMail($item_id, $amount)
    {
        if (count($item_id) == 1) {
            $this->insertMailAttachment($item_id[0], $amount, 1);
        } else {
            $index = 0;
            foreach ($item_id as $key) {
                $this->insertMailAttachment($key, ($index == 1 ? 1 : $amount), $index);
                $index++;
            }
        }
    }

    public function closetsBox($amount, $prices)
    {
        sort($prices);
        $closest = null;
        $next = null;
        foreach ($prices as $index => $price) {
            if ($price > $amount) {
                $next = $price;
                break;
            }
            $closest = $price;
            $closest_index = $index;
        }
        if (is_null($closest)) {
            return $next;
        }
        if (is_null($next)) {
            return $closest_index;
        }
        if (($next - $amount) < ($amount - $closest)) {
            return $closest_index;
        }
        return $closest_index;
    }

    public function setDonationBox($amount)
    {
        $prices = array(4.99, 9.99, 14.99, 19.99, 29.99, 49.99, 79.99, 149.99);
        $box_ids = array("40019", "40020", "40021", "40022", "40023", "40024", "40025", "40026");

        if ($amount < min($prices)) return 0;

        $closest = $this->closetsBox($amount, $prices);
        return $box_ids[$closest];
    }

    public function sendMail($char_name, $char_id, $amount, $message, $item_id)
    {
        DB::table('mail')->insert([
            'send_name' => env('APP_NAME', 'xPanel'),
            'send_id' => 1,
            'dest_name' => $char_name,
            'dest_id' => $char_id,
            'title' => '~ Transfer ' . env('APP_NAME', 'xPanel') . '',
            'message' => $message,
            'time' => strtotime("+1 month"),
            'status' => 1,
        ]);
        $this->sendItemMail($item_id, $amount);
    }

    public function sendByRodex($char_name, $char_id, $amount, $item_id)
    {
        $message = "~ Thank you for using the " . env('APP_NAME', 'xPanel') . " Transfer system. Enjoy and don't forget to invite your friends!";
        $this->sendMail($char_name, $char_id, $amount, $message, $item_id);
    }

    private function setVariabePoints($login, $amount)
    {
        $current_amount = AccRegNum::where('account_id', $login['account_id'])->where('key', Config::get('xpanel.donations.donation_variable'))->first()->value ?? 0;
        if (AccRegNum::where('account_id', $login['account_id'])->where('key', Config::get('xpanel.donations.donation_variable'))->exists()) {
            AccRegNum::where('account_id', $login['account_id'])->where('key', Config::get('xpanel.donations.donation_variable'))->update(['value' => $current_amount + $amount]);
        } else {
            AccRegNum::create([
                'account_id' => $login['account_id'],
                'key' => Config::get('xpanel.donations.donation_variable'),
                'value' => $current_amount + $amount
            ]);
        }
    }

    public function transferMaster2Char($master_id, $var_type, $amount, $transferType)
    {
        $log = new LogWriter(new LogTransfer());
        switch ($transferType) {
            case "Donation Points":
                $this->less_donapoints($master_id, $amount);
                $transferType = Config::get('xpanel.donations.transfer_type');

                if ($transferType === "item") {
                    $char_id = Char::where('name', $var_type)->first()->char_id;
                    $this->sendByRodex($var_type, $char_id, $amount, array(Config::get('xpanel.donations.donation_item_id'), 0));
                } else if ($transferType === "variable") {
                    $login = GameAccount::where('userid', $var_type)->first();
                    $this->setVariabePoints($login, $amount);
                }
                break;

            case "Vote Points":
                $login = GameAccount::where('userid', $var_type)->first();
                $current_amount = AccRegNum::where('account_id', $login['account_id'])->where('key', '#VOTEPOINTS')->first()->value ?? 0;
                User::where('id', $master_id)->decrement('vote_points', $amount);

                if (AccRegNum::where('account_id', $login['account_id'])->where('key', '#VOTEPOINTS')->exists()) {
                    AccRegNum::where('account_id', $login['account_id'])->where('key', '#VOTEPOINTS')->update(['value' => $current_amount + $amount]);
                } else {
                    AccRegNum::create([
                        'account_id' => $login['account_id'],
                        'key' => '#VOTEPOINTS',
                        'value' => $current_amount + $amount
                    ]);
                }
                break;
            default:
                return;
        }

        $data = [
            'master_id' => $master_id,
            'transfer_type' => $transferType,
            'account' => GameAccount::where('userid', $var_type)->first()->userid ?? 'No Apply',
            'char' => ($transferType === "Donation Points") ? Char::where('name', $var_type)->first()->name : 'No Apply',
            'amount' => $amount,
            'date' => date('Y-m-d H:i:s'),
            'ip' => $this->getRealIP(),
        ];

        $log->writeInTable($data);
    }

    public function getDonationBonus($amount)
    {
        $amount = (int) $amount;
        $bonificaciones = Config::get('donations.packages');
        $bono_by_price = [];

        foreach ($bonificaciones as $bonificacion) {
            $bono_by_price[$bonificacion['price']] = $bonificacion['extra_points'];
        }

        return $bono_by_price[$amount] ?? 0;
    }
}
