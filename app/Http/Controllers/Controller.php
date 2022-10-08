<?php

namespace App\Http\Controllers;

use App\Traits\ModelTrait;
use App\Traits\NoAuthTrait;
use App\Traits\NubanVerify;
use App\Traits\CountryTrait;
use App\Traits\BulkMailTrait;
use App\Traits\SettingsTrait;
use App\Traits\DashboardTrait;
use App\Traits\FrontEndTraits;
use App\Traits\Files\FilesTrait;
use App\Traits\NowPaymentsTrait;
use App\Traits\ChangeGateayTrait;
use App\Traits\PaymentModalTrait;
use App\Traits\DisplayBannerTraits;
use App\Traits\Support\SupportTrait;
use App\Traits\Pagination\PaginationTrait;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Traits\CoinConversionRate\CoinConversionRateTrait;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, PaginationTrait, ChangeGateayTrait, NowPaymentsTrait, CoinConversionRateTrait, SettingsTrait, SupportTrait, FilesTrait, BulkMailTrait, FrontEndTraits, PaymentModalTrait, NoAuthTrait, DisplayBannerTraits, DashboardTrait, CountryTrait, NubanVerify;
}