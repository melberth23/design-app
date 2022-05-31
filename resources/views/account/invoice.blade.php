<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice - DO{{ $invoice->number }}</title>

    <style type="text/css">
        @page {
            margin: 0px;
        }
        body {
            margin: 0px;
        }
        * {
            font-family: Verdana, Arial, sans-serif;
        }
        a {
            color: #fff;
            text-decoration: none;
        }
        table {
            font-size: x-small;
        }
        tfoot tr td {
            font-weight: bold;
            font-size: x-small;
        }
        .invoice table {
            margin: 15px;
        }
        .invoice h3 {
            margin-left: 15px;
        }
        .information {
            background-color: #9672FB;
            color: #FFF;
        }
        .information .logo {
            margin: 5px;
        }
        .information table {
            padding: 10px;
        }
    </style>

</head>
<body>

<div class="information">
    <table width="100%">
        <tr>
            <td align="left" style="width: 50%;">
                <h3>{{ $user->first_name }} {{ $user->last_name }}</h3>
                Date: {{ $invoice->created_at->format('d F, Y') }}
                <br /><br />
                {{ $user->address_1 }} {{ $user->address_2 }},<br />
                {{ $user->city }} {{ $user->state }}, {{ $user->zip }}<br />
                {{ $user->country }}
            </td>
            <td align="right" style="width: 50%;">

                <h3>DesignsOwl</h3>
                <br /><br />
                {{ config('app.url') }}
            </td>
        </tr>

    </table>
</div>


<br/>

<div class="invoice">
    <h3>Invoice DO{{ $invoice->number }}</h3>
    <table width="100%">
        <thead>
        <tr>
            <th align="left">Description</th>
            <th align="left">Plan</th>
            <th align="left">Total</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td align="left">Invoice for {{ $invoice->created_at->format('d F, Y') }}</td>
            <td align="left">Designs Owl, {{ (new \App\Lib\SystemHelper)->getPlanInformation($invoice->plan)['label'] }}</td>
            <td align="left">{{ (new \App\Lib\SystemHelper)->getCurrency('', 'symbol') }}{{ number_format($invoice->amount) }}</td>
        </tr>
        </tbody>

        <tfoot>
            <tr>
                <td colspan="1"></td>
                <td align="left">Total</td>
                <td align="left" class="gray">{{ (new \App\Lib\SystemHelper)->getCurrency('', 'symbol') }}{{ number_format($invoice->amount) }}</td>
            </tr>
        </tfoot>
    </table>
</div>

<div class="information" style="position: absolute; bottom: 0;width: 100%;">
    <table width="100%">
        <tr>
            <td align="left" style="width: 50%;">
                &copy; {{ date('Y') }} {{ config('app.url') }} - All rights reserved.
            </td>
            <td align="right" style="width: 50%;">
                {{ config('app.name') }}
            </td>
        </tr>

    </table>
</div>
</body>