<?php 
namespace App\Lib;


use DB;
use DateTimeZone;
use App\Models\Payments;
use App\Models\Requests;
use App\Models\Brand;
use App\Models\BrandAssets;
use App\Models\UserSettings;
use App\Models\Comments;
use App\Models\CommentNotification;
use App\Models\User;

class SystemHelper {

    /**
    * @param string plan
    * @return array Plan information
    */
    public function getPlanRules($plan='basic') {
        return $this->getPlanInformation($plan);
    }

    /**
    * @param string plan
    * @return array Plan information
    */
    public function getPlanInformation($plan='basic', $duration='monthly')
    {
        $plans = array(
            'monthly' => array(
                'basic' => array(
                    'label' => 'Basic',
                    'id' => '95d369cc-7fbd-4608-b53f-562605eab522',
                    'amount' => 399,
                    'request' => 1,
                    'backlog' => false,
                    'brand' => 1
                ),
                'premium' => array(
                    'label' => 'Premium',
                    'id' => '95d369fd-9228-4f36-bd7f-d03c6805027a',
                    'amount' => 599,
                    'request' => 2,
                    'backlog' => false,
                    'brand' => 2
                ),
                'royal' => array(
                    'label' => 'Royal',
                    'id' => '95d36a21-671d-48f9-909f-002d022c6b59',
                    'amount' => 2395,
                    'request' => 2,
                    'backlog' => true,
                    'brand' => 9999
                )
            ),
            'yearly' => array(
                'basic' => array(
                    'label' => 'Basic',
                    'id' => '95d32cca-1c37-4efc-a0f5-48203c8386f7',
                    'amount' => 4310,
                    'request' => 1,
                    'backlog' => false,
                    'brand' => 1
                ),
                'premium' => array(
                    'label' => 'Premium',
                    'id' => '9699a9c9-63e4-4895-9b01-cf7fa598dcc4',
                    'amount' => 6470,
                    'request' => 2,
                    'backlog' => false,
                    'brand' => 2
                ),
                'royal' => array(
                    'label' => 'Royal',
                    'id' => '9699aa3b-6196-426b-ac73-7627b7d94780',
                    'amount' => 3795,
                    'request' => 2,
                    'backlog' => true,
                    'brand' => 9999
                )
            )
        );

        return $plans[$duration][$plan];
    }

    /**
     * Check if plan is lower or upper 
     * @param plan
     * @return plan information
     */
    public function planPositionChecker($plan='', $currentplan='')
    {
        if(!empty($plan) && !empty($currentplan)) {
            if($this->getPlanInformation($plan)['amount'] > $this->getPlanInformation($currentplan)['amount']) {
                return true;
            }
        }
        return false;
    }

    /**
    * @param int userid
    * @return array allowed information
    */
    public function userActionRules($userid, $type='request', $status=1) {
        $statusRules = array(2,3);
        $numberofitems = array();
        if($type == 'brand') {
            $numberofitems = Brand::where('user_id', $userid)->get();
        } elseif($type == 'request') {
            if($status == 2) {
                $numberofitems = Requests::whereIn('status', $statusRules)->where('user_id', $userid)->get();
            }
        }
        $numberofitems = count($numberofitems);
        $user = User::whereId($userid)->first();
        $planrule = $this->getPlanRules($user->payments->plan);
        $allowedrule = $planrule[$type];

        $allowed = false;
        if($allowedrule > 0) {
            if($allowedrule > $numberofitems) {
                $allowed = true;
            }
        } else {
            $allowed = true;
        }

        return array(
            'allowed' => $allowed,
            'allowedrequest' => $planrule['request'],
            'allowedbrand' => $planrule['brand']
        );
    }

    /**
    * @param int length of generated string
    * @return string Random string
    */
    public function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
    * @param int userid
    * @return bool true | false
    */
    public function checkIfAccountPaid($userid) {
        $isPaid = Payments::where('user_id', $userid)->IsPaid();
        if($isPaid)
        {
            return true;
        }
        return false;
    }

    /**
    * @param int status
    * @return string status label
    */
    public function checkLockedStatus($status) {
        $statuses = array(
            0 => 'Completed',
            3 => 'Progress'
        );
        return !empty($statuses[$status])?true:false;
    }

    /**
    * @param int status
    * @return string status label
    */
    public function statusLabel($status) {
        $statuses = array(
            0 => 'Completed',
            1 => 'Draft',
            2 => 'Submitted',
            3 => 'Progress',
            4 => 'For Review'
        );

        return $statuses[$status];
    }

    /**
     * @param string file type
     * @return array directories
     */
    public function media_directories($type) {

        $type = ($type=='review')?'comment':$type;

        $directories = array(
            'logo' => 'logos',
            'logo_second' => 'logos',
            'picture' => 'pictures',
            'font' => 'fonts',
            'font_second' => 'fonts',
            'inspiration' => 'inspirations',
            'template' => 'templates',
            'guideline' => 'guidelines',
            'media' => 'media',
            'comment' => 'comments',
        );

        return $directories[$type];
    }

    /**
     * @return all file types 
     */
    public function request_file_types() {
        $file_types = array(
            'jpg' => '.jpg',
            'png' => '.png',
            'gif' => '.gif',
            'pdf' => '.pdf',
            'any' => '.any'
        );
        $adobe_types = array(
            'psd' => 'psd',
            'ai' => 'ai',
            'indd' => 'indd'
        );

        return [
            'files' => $file_types,
            'adobe' => $adobe_types
        ];
    }

    public function get_brand_logo($brand) {
        $logos = BrandAssets::where('brand_id', $brand->id)->where('type', 'logo')->first();

        $string = '<h2>'. substr($brand->name, 0, 1) .'.</h2>';
        if (!empty($logos) && $logos->count() > 0) {
            $string = '<img src="'. url('storage/logos') .'/'.$brand->user_id .'/'. $logos->filename .'" class="main-logo" >';
        }

        return $string;
    }

    public function get_brand_assets($brand, $type) {
        $assets = BrandAssets::where('brand_id', $brand->id)->where('type', $type)->get();

        $string = '';
        if($type == 'color' && $assets->count() > 0) {
            foreach($assets as $asset) {
                $string .= '<div class="mx-1 color" style="background-color: '. $asset->filename .';border-radius: 50px; width: 40px; height: 40px;"></div>';
            }
        }

        return $string;
    }

    public function reasons($key=false)
    {
        $reasons = array(
            'trouble'       => 'Trouble getting started',
            'distracting'   => 'Too busy/too distracting',
            'privacy'       => 'Privacy Concerns',
            'second_account'    => 'Created a second account',
            'notification'  => 'Too many email notification',
            'break'         => 'Just need a break',
            'not_to_say'    => 'Prefer not to say'
        );

        if($key) {
            return !empty($reasons[$key])?$reasons[$key]:false;
        } else {
            return $reasons;
        }
    }

    public function updateOrCreateUserSetting($userid, $name, $value)
    {
        $setting = UserSettings::where('user_id', $userid)->where('name', $name)->first();
        if(!empty($setting)) {
            UserSettings::where('user_id', $userid)->where('name', $name)->update(['value' => $value]);
        } else {
            UserSettings::create([
                'user_id' => $userid,
                'name' => $name,
                'value' => $value
            ]);
        }
    }

    public function getUserSetting($userid, $name)
    {
        $setting = UserSettings::where('user_id', $userid)->where('name', $name)->first();
        return !empty($setting)?$setting->value:false;
    }

    public function timezones()
    {
        $tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
        return $tzlist;
    }

    public function getDefaultTimezone()
    {

    }

    public function getLanguages($lang=false)
    {
        $languages_list = array(
            'af' => 'Afrikaans',
            'sq' => 'Albanian - shqip',
            'am' => 'Amharic - አማርኛ',
            'ar' => 'Arabic - العربية',
            'an' => 'Aragonese - aragonés',
            'hy' => 'Armenian - հայերեն',
            'ast' => 'Asturian - asturianu',
            'az' => 'Azerbaijani - azərbaycan dili',
            'eu' => 'Basque - euskara',
            'be' => 'Belarusian - беларуская',
            'bn' => 'Bengali - বাংলা',
            'bs' => 'Bosnian - bosanski',
            'br' => 'Breton - brezhoneg',
            'bg' => 'Bulgarian - български',
            'ca' => 'Catalan - català',
            'ckb' => 'Central Kurdish - کوردی (دەستنوسی عەرەبی)',
            'zh' => 'Chinese - 中文',
            'zh-HK' => 'Chinese (Hong Kong) - 中文（香港）',
            'zh-CN' => 'Chinese (Simplified) - 中文（简体）',
            'zh-TW' => 'Chinese (Traditional) - 中文（繁體）',
            'co' => 'Corsican',
            'hr' => 'Croatian - hrvatski',
            'cs' => 'Czech - čeština',
            'da' => 'Danish - dansk',
            'nl' => 'Dutch - Nederlands',
            'en' => 'English',
            'en-AU' => 'English (Australia)',
            'en-CA' => 'English (Canada)',
            'en-IN' => 'English (India)',
            'en-NZ' => 'English (New Zealand)',
            'en-ZA' => 'English (South Africa)',
            'en-GB' => 'English (United Kingdom)',
            'en-US' => 'English (United States)',
            'eo' => 'Esperanto - esperanto',
            'et' => 'Estonian - eesti',
            'fo' => 'Faroese - føroyskt',
            'fil' => 'Filipino',
            'fi' => 'Finnish - suomi',
            'fr' => 'French - français',
            'fr-CA' => 'French (Canada) - français (Canada)',
            'fr-FR' => 'French (France) - français (France)',
            'fr-CH' => 'French (Switzerland) - français (Suisse)',
            'gl' => 'Galician - galego',
            'ka' => 'Georgian - ქართული',
            'de' => 'German - Deutsch',
            'de-AT' => 'German (Austria) - Deutsch (Österreich)',
            'de-DE' => 'German (Germany) - Deutsch (Deutschland)',
            'de-LI' => 'German (Liechtenstein) - Deutsch (Liechtenstein)',
            'de-CH' => 'German (Switzerland) - Deutsch (Schweiz)',
            'el' => 'Greek - Ελληνικά',
            'gn' => 'Guarani',
            'gu' => 'Gujarati - ગુજરાતી',
            'ha' => 'Hausa',
            'haw' => 'Hawaiian - ʻŌlelo Hawaiʻi',
            'he' => 'Hebrew - עברית',
            'hi' => 'Hindi - हिन्दी',
            'hu' => 'Hungarian - magyar',
            'is' => 'Icelandic - íslenska',
            'id' => 'Indonesian - Indonesia',
            'ia' => 'Interlingua',
            'ga' => 'Irish - Gaeilge',
            'it' => 'Italian - italiano',
            'it-IT' => 'Italian (Italy) - italiano (Italia)',
            'it-CH' => 'Italian (Switzerland) - italiano (Svizzera)',
            'ja' => 'Japanese - 日本語',
            'kn' => 'Kannada - ಕನ್ನಡ',
            'kk' => 'Kazakh - қазақ тілі',
            'km' => 'Khmer - ខ្មែរ',
            'ko' => 'Korean - 한국어',
            'ku' => 'Kurdish - Kurdî',
            'ky' => 'Kyrgyz - кыргызча',
            'lo' => 'Lao - ລາວ',
            'la' => 'Latin',
            'lv' => 'Latvian - latviešu',
            'ln' => 'Lingala - lingála',
            'lt' => 'Lithuanian - lietuvių',
            'mk' => 'Macedonian - македонски',
            'ms' => 'Malay - Bahasa Melayu',
            'ml' => 'Malayalam - മലയാളം',
            'mt' => 'Maltese - Malti',
            'mr' => 'Marathi - मराठी',
            'mn' => 'Mongolian - монгол',
            'ne' => 'Nepali - नेपाली',
            'no' => 'Norwegian - norsk',
            'nb' => 'Norwegian Bokmål - norsk bokmål',
            'nn' => 'Norwegian Nynorsk - nynorsk',
            'oc' => 'Occitan',
            'or' => 'Oriya - ଓଡ଼ିଆ',
            'om' => 'Oromo - Oromoo',
            'ps' => 'Pashto - پښتو',
            'fa' => 'Persian - فارسی',
            'pl' => 'Polish - polski',
            'pt' => 'Portuguese - português',
            'pt-BR' => 'Portuguese (Brazil) - português (Brasil)',
            'pt-PT' => 'Portuguese (Portugal) - português (Portugal)',
            'pa' => 'Punjabi - ਪੰਜਾਬੀ',
            'qu' => 'Quechua',
            'ro' => 'Romanian - română',
            'mo' => 'Romanian (Moldova) - română (Moldova)',
            'rm' => 'Romansh - rumantsch',
            'ru' => 'Russian - русский',
            'gd' => 'Scottish Gaelic',
            'sr' => 'Serbian - српски',
            'sh' => 'Serbo-Croatian - Srpskohrvatski',
            'sn' => 'Shona - chiShona',
            'sd' => 'Sindhi',
            'si' => 'Sinhala - සිංහල',
            'sk' => 'Slovak - slovenčina',
            'sl' => 'Slovenian - slovenščina',
            'so' => 'Somali - Soomaali',
            'st' => 'Southern Sotho',
            'es' => 'Spanish - español',
            'es-AR' => 'Spanish (Argentina) - español (Argentina)',
            'es-419' => 'Spanish (Latin America) - español (Latinoamérica)',
            'es-MX' => 'Spanish (Mexico) - español (México)',
            'es-ES' => 'Spanish (Spain) - español (España)',
            'es-US' => 'Spanish (United States) - español (Estados Unidos)',
            'su' => 'Sundanese',
            'sw' => 'Swahili - Kiswahili',
            'sv' => 'Swedish - svenska',
            'tg' => 'Tajik - тоҷикӣ',
            'ta' => 'Tamil - தமிழ்',
            'tt' => 'Tatar',
            'te' => 'Telugu - తెలుగు',
            'th' => 'Thai - ไทย',
            'ti' => 'Tigrinya - ትግርኛ',
            'to' => 'Tongan - lea fakatonga',
            'tr' => 'Turkish - Türkçe',
            'tk' => 'Turkmen',
            'tw' => 'Twi',
            'uk' => 'Ukrainian - українська',
            'ur' => 'Urdu - اردو',
            'ug' => 'Uyghur',
            'uz' => 'Uzbek - o‘zbek',
            'vi' => 'Vietnamese - Tiếng Việt',
            'wa' => 'Walloon - wa',
            'cy' => 'Welsh - Cymraeg',
            'fy' => 'Western Frisian',
            'xh' => 'Xhosa',
            'yi' => 'Yiddish',
            'yo' => 'Yoruba - Èdè Yorùbá',
            'zu' => 'Zulu - isiZulu'
        );
        
        if($lang) {
            return !empty($languages_list[$lang])?$languages_list[$lang]:false;
        } else {
            return $languages_list;
        }
    }

    public function getCurrencies($currency=false)
    {
        $currency_list = array(
            "AFA" => array("name" => "Afghan Afghani", "symbol" => "؋"),
            "ALL" => array("name" => "Albanian Lek", "symbol" => "Lek"),
            "DZD" => array("name" => "Algerian Dinar", "symbol" => "دج"),
            "AOA" => array("name" => "Angolan Kwanza", "symbol" => "Kz"),
            "ARS" => array("name" => "Argentine Peso", "symbol" => "$"),
            "AMD" => array("name" => "Armenian Dram", "symbol" => "֏"),
            "AWG" => array("name" => "Aruban Florin", "symbol" => "ƒ"),
            "AUD" => array("name" => "Australian Dollar", "symbol" => "$"),
            "AZN" => array("name" => "Azerbaijani Manat", "symbol" => "m"),
            "BSD" => array("name" => "Bahamian Dollar", "symbol" => "B$"),
            "BHD" => array("name" => "Bahraini Dinar", "symbol" => ".د.ب"),
            "BDT" => array("name" => "Bangladeshi Taka", "symbol" => "৳"),
            "BBD" => array("name" => "Barbadian Dollar", "symbol" => "Bds$"),
            "BYR" => array("name" => "Belarusian Ruble", "symbol" => "Br"),
            "BEF" => array("name" => "Belgian Franc", "symbol" => "fr"),
            "BZD" => array("name" => "Belize Dollar", "symbol" => "$"),
            "BMD" => array("name" => "Bermudan Dollar", "symbol" => "$"),
            "BTN" => array("name" => "Bhutanese Ngultrum", "symbol" => "Nu."),
            "BTC" => array("name" => "Bitcoin", "symbol" => "฿"),
            "BOB" => array("name" => "Bolivian Boliviano", "symbol" => "Bs."),
            "BAM" => array("name" => "Bosnia", "symbol" => "KM"),
            "BWP" => array("name" => "Botswanan Pula", "symbol" => "P"),
            "BRL" => array("name" => "Brazilian Real", "symbol" => "R$"),
            "GBP" => array("name" => "British Pound Sterling", "symbol" => "£"),
            "BND" => array("name" => "Brunei Dollar", "symbol" => "B$"),
            "BGN" => array("name" => "Bulgarian Lev", "symbol" => "Лв."),
            "BIF" => array("name" => "Burundian Franc", "symbol" => "FBu"),
            "KHR" => array("name" => "Cambodian Riel", "symbol" => "KHR"),
            "CAD" => array("name" => "Canadian Dollar", "symbol" => "$"),
            "CVE" => array("name" => "Cape Verdean Escudo", "symbol" => "$"),
            "KYD" => array("name" => "Cayman Islands Dollar", "symbol" => "$"),
            "XOF" => array("name" => "CFA Franc BCEAO", "symbol" => "CFA"),
            "XAF" => array("name" => "CFA Franc BEAC", "symbol" => "FCFA"),
            "XPF" => array("name" => "CFP Franc", "symbol" => "₣"),
            "CLP" => array("name" => "Chilean Peso", "symbol" => "$"),
            "CNY" => array("name" => "Chinese Yuan", "symbol" => "¥"),
            "COP" => array("name" => "Colombian Peso", "symbol" => "$"),
            "KMF" => array("name" => "Comorian Franc", "symbol" => "CF"),
            "CDF" => array("name" => "Congolese Franc", "symbol" => "FC"),
            "CRC" => array("name" => "Costa Rican ColÃ³n", "symbol" => "₡"),
            "HRK" => array("name" => "Croatian Kuna", "symbol" => "kn"),
            "CUC" => array("name" => "Cuban Convertible Peso", "symbol" => "$, CUC"),
            "CZK" => array("name" => "Czech Republic Koruna", "symbol" => "Kč"),
            "DKK" => array("name" => "Danish Krone", "symbol" => "Kr."),
            "DJF" => array("name" => "Djiboutian Franc", "symbol" => "Fdj"),
            "DOP" => array("name" => "Dominican Peso", "symbol" => "$"),
            "XCD" => array("name" => "East Caribbean Dollar", "symbol" => "$"),
            "EGP" => array("name" => "Egyptian Pound", "symbol" => "ج.م"),
            "ERN" => array("name" => "Eritrean Nakfa", "symbol" => "Nfk"),
            "EEK" => array("name" => "Estonian Kroon", "symbol" => "kr"),
            "ETB" => array("name" => "Ethiopian Birr", "symbol" => "Nkf"),
            "EUR" => array("name" => "Euro", "symbol" => "€"),
            "FKP" => array("name" => "Falkland Islands Pound", "symbol" => "£"),
            "FJD" => array("name" => "Fijian Dollar", "symbol" => "FJ$"),
            "GMD" => array("name" => "Gambian Dalasi", "symbol" => "D"),
            "GEL" => array("name" => "Georgian Lari", "symbol" => "ლ"),
            "DEM" => array("name" => "German Mark", "symbol" => "DM"),
            "GHS" => array("name" => "Ghanaian Cedi", "symbol" => "GH₵"),
            "GIP" => array("name" => "Gibraltar Pound", "symbol" => "£"),
            "GRD" => array("name" => "Greek Drachma", "symbol" => "₯, Δρχ, Δρ"),
            "GTQ" => array("name" => "Guatemalan Quetzal", "symbol" => "Q"),
            "GNF" => array("name" => "Guinean Franc", "symbol" => "FG"),
            "GYD" => array("name" => "Guyanaese Dollar", "symbol" => "$"),
            "HTG" => array("name" => "Haitian Gourde", "symbol" => "G"),
            "HNL" => array("name" => "Honduran Lempira", "symbol" => "L"),
            "HKD" => array("name" => "Hong Kong Dollar", "symbol" => "$"),
            "HUF" => array("name" => "Hungarian Forint", "symbol" => "Ft"),
            "ISK" => array("name" => "Icelandic KrÃ³na", "symbol" => "kr"),
            "INR" => array("name" => "Indian Rupee", "symbol" => "₹"),
            "IDR" => array("name" => "Indonesian Rupiah", "symbol" => "Rp"),
            "IRR" => array("name" => "Iranian Rial", "symbol" => "﷼"),
            "IQD" => array("name" => "Iraqi Dinar", "symbol" => "د.ع"),
            "ILS" => array("name" => "Israeli New Sheqel", "symbol" => "₪"),
            "ITL" => array("name" => "Italian Lira", "symbol" => "L,£"),
            "JMD" => array("name" => "Jamaican Dollar", "symbol" => "J$"),
            "JPY" => array("name" => "Japanese Yen", "symbol" => "¥"),
            "JOD" => array("name" => "Jordanian Dinar", "symbol" => "ا.د"),
            "KZT" => array("name" => "Kazakhstani Tenge", "symbol" => "лв"),
            "KES" => array("name" => "Kenyan Shilling", "symbol" => "KSh"),
            "KWD" => array("name" => "Kuwaiti Dinar", "symbol" => "ك.د"),
            "KGS" => array("name" => "Kyrgystani Som", "symbol" => "лв"),
            "LAK" => array("name" => "Laotian Kip", "symbol" => "₭"),
            "LVL" => array("name" => "Latvian Lats", "symbol" => "Ls"),
            "LBP" => array("name" => "Lebanese Pound", "symbol" => "£"),
            "LSL" => array("name" => "Lesotho Loti", "symbol" => "L"),
            "LRD" => array("name" => "Liberian Dollar", "symbol" => "$"),
            "LYD" => array("name" => "Libyan Dinar", "symbol" => "د.ل"),
            "LTL" => array("name" => "Lithuanian Litas", "symbol" => "Lt"),
            "MOP" => array("name" => "Macanese Pataca", "symbol" => "$"),
            "MKD" => array("name" => "Macedonian Denar", "symbol" => "ден"),
            "MGA" => array("name" => "Malagasy Ariary", "symbol" => "Ar"),
            "MWK" => array("name" => "Malawian Kwacha", "symbol" => "MK"),
            "MYR" => array("name" => "Malaysian Ringgit", "symbol" => "RM"),
            "MVR" => array("name" => "Maldivian Rufiyaa", "symbol" => "Rf"),
            "MRO" => array("name" => "Mauritanian Ouguiya", "symbol" => "MRU"),
            "MUR" => array("name" => "Mauritian Rupee", "symbol" => "₨"),
            "MXN" => array("name" => "Mexican Peso", "symbol" => "$"),
            "MDL" => array("name" => "Moldovan Leu", "symbol" => "L"),
            "MNT" => array("name" => "Mongolian Tugrik", "symbol" => "₮"),
            "MAD" => array("name" => "Moroccan Dirham", "symbol" => "MAD"),
            "MZM" => array("name" => "Mozambican Metical", "symbol" => "MT"),
            "MMK" => array("name" => "Myanmar Kyat", "symbol" => "K"),
            "NAD" => array("name" => "Namibian Dollar", "symbol" => "$"),
            "NPR" => array("name" => "Nepalese Rupee", "symbol" => "₨"),
            "ANG" => array("name" => "Netherlands Antillean Guilder", "symbol" => "ƒ"),
            "TWD" => array("name" => "New Taiwan Dollar", "symbol" => "$"),
            "NZD" => array("name" => "New Zealand Dollar", "symbol" => "$"),
            "NIO" => array("name" => "Nicaraguan CÃ³rdoba", "symbol" => "C$"),
            "NGN" => array("name" => "Nigerian Naira", "symbol" => "₦"),
            "KPW" => array("name" => "North Korean Won", "symbol" => "₩"),
            "NOK" => array("name" => "Norwegian Krone", "symbol" => "kr"),
            "OMR" => array("name" => "Omani Rial", "symbol" => ".ع.ر"),
            "PKR" => array("name" => "Pakistani Rupee", "symbol" => "₨"),
            "PAB" => array("name" => "Panamanian Balboa", "symbol" => "B/."),
            "PGK" => array("name" => "Papua New Guinean Kina", "symbol" => "K"),
            "PYG" => array("name" => "Paraguayan Guarani", "symbol" => "₲"),
            "PEN" => array("name" => "Peruvian Nuevo Sol", "symbol" => "S/."),
            "PHP" => array("name" => "Philippine Peso", "symbol" => "₱"),
            "PLN" => array("name" => "Polish Zloty", "symbol" => "zł"),
            "QAR" => array("name" => "Qatari Rial", "symbol" => "ق.ر"),
            "RON" => array("name" => "Romanian Leu", "symbol" => "lei"),
            "RUB" => array("name" => "Russian Ruble", "symbol" => "₽"),
            "RWF" => array("name" => "Rwandan Franc", "symbol" => "FRw"),
            "SVC" => array("name" => "Salvadoran ColÃ³n", "symbol" => "₡"),
            "WST" => array("name" => "Samoan Tala", "symbol" => "SAT"),
            "SAR" => array("name" => "Saudi Riyal", "symbol" => "﷼"),
            "RSD" => array("name" => "Serbian Dinar", "symbol" => "din"),
            "SCR" => array("name" => "Seychellois Rupee", "symbol" => "SRe"),
            "SLL" => array("name" => "Sierra Leonean Leone", "symbol" => "Le"),
            "SGD" => array("name" => "Singapore Dollar", "symbol" => "$"),
            "SKK" => array("name" => "Slovak Koruna", "symbol" => "Sk"),
            "SBD" => array("name" => "Solomon Islands Dollar", "symbol" => "Si$"),
            "SOS" => array("name" => "Somali Shilling", "symbol" => "Sh.so."),
            "ZAR" => array("name" => "South African Rand", "symbol" => "R"),
            "KRW" => array("name" => "South Korean Won", "symbol" => "₩"),
            "XDR" => array("name" => "Special Drawing Rights", "symbol" => "SDR"),
            "LKR" => array("name" => "Sri Lankan Rupee", "symbol" => "Rs"),
            "SHP" => array("name" => "St. Helena Pound", "symbol" => "£"),
            "SDG" => array("name" => "Sudanese Pound", "symbol" => ".س.ج"),
            "SRD" => array("name" => "Surinamese Dollar", "symbol" => "$"),
            "SZL" => array("name" => "Swazi Lilangeni", "symbol" => "E"),
            "SEK" => array("name" => "Swedish Krona", "symbol" => "kr"),
            "CHF" => array("name" => "Swiss Franc", "symbol" => "CHf"),
            "SYP" => array("name" => "Syrian Pound", "symbol" => "LS"),
            "STD" => array("name" => "São Tomé and Príncipe Dobra", "symbol" => "Db"),
            "TJS" => array("name" => "Tajikistani Somoni", "symbol" => "SM"),
            "TZS" => array("name" => "Tanzanian Shilling", "symbol" => "TSh"),
            "THB" => array("name" => "Thai Baht", "symbol" => "฿"),
            "TOP" => array("name" => "Tongan pa'anga", "symbol" => "$"),
            "TTD" => array("name" => "Trinidad & Tobago Dollar", "symbol" => "$"),
            "TND" => array("name" => "Tunisian Dinar", "symbol" => "ت.د"),
            "TRY" => array("name" => "Turkish Lira", "symbol" => "₺"),
            "TMT" => array("name" => "Turkmenistani Manat", "symbol" => "T"),
            "UGX" => array("name" => "Ugandan Shilling", "symbol" => "USh"),
            "UAH" => array("name" => "Ukrainian Hryvnia", "symbol" => "₴"),
            "AED" => array("name" => "United Arab Emirates Dirham", "symbol" => "إ.د"),
            "UYU" => array("name" => "Uruguayan Peso", "symbol" => "$"),
            "USD" => array("name" => "US Dollar", "symbol" => "$"),
            "UZS" => array("name" => "Uzbekistan Som", "symbol" => "лв"),
            "VUV" => array("name" => "Vanuatu Vatu", "symbol" => "VT"),
            "VEF" => array("name" => "Venezuelan BolÃvar", "symbol" => "Bs"),
            "VND" => array("name" => "Vietnamese Dong", "symbol" => "₫"),
            "YER" => array("name" => "Yemeni Rial", "symbol" => "﷼"),
            "ZMK" => array("name" => "Zambian Kwacha", "symbol" => "ZK")
        );

        if($currency) {
            return !empty($currency_list[$currency])?$currency_list[$currency]:false;
        } else {
            return $currency_list;
        }
    }

    public function getLanguage($lang=false)
    {
        if(empty($lang)) {
            $lang = $this->getUserSetting(auth()->user()->id, 'language');
        }
        return !empty($lang)?$this->getLanguages($lang):false;
    }

    public function getCurrency($cur=false, $type='')
    {
        if(empty($cur)) {
            $cur = $this->getUserSetting(auth()->user()->id, 'currency');
        }
        if(!empty($cur)) {
            $currency = $this->getCurrencies($cur);
            if(!empty($type)) {
                return $currency[$type];
            }
            return !empty($currency)?'('. $currency['symbol'] .')'. $currency['name'] : '';
        }
        return false;
    }

    public function getNotifications()
    {
        $notifications = CommentNotification::where('user_id', auth()->user()->id)->where('read', 0)->get();
        return array(
            'counter' => ($notifications->count()>100)?'999+':$notifications->count(),
            'lists' => $this->getNotificationInformation($notifications)
        );
    }

    public function getNotificationInformation($notifications)
    {
        $lists = [];
        if($notifications->count() > 0) {
            foreach($notifications as $notification) {
                $comment = Comments::whereId($notification->comment_id)->first();
                $lists[] = array(
                    'request_id' => $comment->request_id,
                    'request_title' => $comment->request->title,
                    'user_name' => $comment->user->first_name
                );
            }
        }

        return $lists;
    }

    public function getCountries()
    {
        
    }
}