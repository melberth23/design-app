<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice - DO{{ $invoice->id }}</title>

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

<div style="padding-left: 3%;padding-right: 3%;">
    <table width="100%">
        <tr>
            <td align="left" style="width: 60%;">
                <img src="{{ asset('images/logo-dark.svg') }}" style="width: 200px; vertical-align: top;">
            </td>
            <td align="right" style="width: 40%;">
                <h2 style="text-align: left;">Invoice Information</h2>
                <hr>
                <table style="width: 100%;">
                    <tr>
                        <td align="left">Invoice Number:</td>
                        <td align="right">DO{{ $invoice->id }}</td>
                    </tr>
                    <tr>
                        <td align="left">Invoice Date:</td>
                        <td align="right">{{ $invoice->created_at->format('d F, Y') }}</td>
                    </tr>
                    <tr>
                        <td align="left">Payment Method:</td>
                        <td align="right">Card</td>
                    </tr>
                    <tr>
                        <td align="left">Customer Number:</td>
                        <td align="right">{{ $user->mobile_number }}</td>
                    </tr>
                </table>
            </td>
        </tr>

    </table>
</div>

<div style="padding-left: 3%;padding-right: 3%;">
    <table width="100%">
        <tr>
            <td align="left" style="font-size: 18px;">
                <h3>Bill To</h3>
                <h4>{{ $user->first_name }} {{ $user->last_name }}</h4>
                {{ $user->address_1 }} {{ $user->address_2 }},<br />
                {{ $user->city }} {{ $user->state }}, {{ $user->zip }}<br />
                {{ $user->country }}
            </td>
        </tr>

    </table>
</div>


<br/>

<div style="padding-left: 3%;padding-right: 3%;">
    <h3 style="font-size: 28px; font-weight: normal;">INVOICE</h3>
    <p style="background-color: #9672FB; color: #FFF; font-size: 18px; padding: 10px 10px; margin: 0; font-weight: bold;">Item Details</p>
    <p style="background-color: #f8f9fc; color: #000; font-size: 18px; padding: 10px 10px; margin: 0; font-weight: bold;">Service Term: <?php echo date('d F, Y', strtotime($invoice->date_invoice)); ?> to <?php echo date('d F, Y', strtotime("+1 month", strtotime($invoice->date_invoice))); ?></p>
</div>

<br/>

<div style="padding-left: 3%;padding-right: 3%;">
    <table width="100%" >
        <tbody>
            <tr style="margin: 0;">
                <td align="left" style="padding: 15px 10px; border-bottom: 1px solid #000; margin: 0;">PRODUCT DESCRIPTION</td>
                <td align="left" style="padding: 15px 10px; border-bottom: 1px solid #000; margin: 0;">PRODUCT PLAN</td>
                <td align="left" style="padding: 15px 10px; border-bottom: 1px solid #000; margin: 0;">UNIT PRICE</td>
                <td align="right" style="padding: 15px 10px; border-bottom: 1px solid #000; margin: 0;">TOTAL</td>
            </tr>
            <tr style="margin: 0;">
                <td align="left" style="padding: 15px 10px; border-bottom: 1px solid #000; margin: 0;"><strong>{{ ucfirst(!empty($payments->duration)?$payments->duration:'monthly') }}</strong></td>
                <td align="left" style="padding: 15px 10px; border-bottom: 1px solid #000; margin: 0;">Designs Owl, {{ (new \App\Lib\SystemHelper)->getPlanInformation($invoice->plan)['label'] }}</td>
                <td align="left" style="padding: 15px 10px; border-bottom: 1px solid #000; margin: 0;">S${{ number_format($invoice->amount, 2) }}</td>
                <td align="right" style="padding: 15px 10px; border-bottom: 1px solid #000; margin: 0;">S${{ number_format($invoice->amount, 2) }}</td>
            </tr>
        </tbody>
    </table>
</div>

<br/>

<div style="padding-left: 3%;padding-right: 3%;">
    <table width="100%" style="background-color: #ddd;">
        <tr style="margin: 0;">
            <td align="left" style="padding: 15px 10px;"><h3 style="font-size: 28px;">Invoice Total</h3></td>
            <td align="right" style="padding: 15px 10px;">NET AMOUNT(SGD)</td>
            <td align="right" style="padding: 15px 10px;">S${{ number_format($invoice->amount, 2) }}</td>
        </tr>
    </table>
    <table width="100%" style="background-color: #000; color: #fff;">
        <tr style="margin: 0;">
            <td align="left" style="padding: 15px 10px;"></td>
            <td align="right" style="padding: 15px 10px;">GRAND TOTAL(SGD)</td>
            <td align="right" style="padding: 15px 10px;">S${{ number_format($invoice->amount, 2) }}</td>
        </tr>
    </table>
</div>

<br/>
<br/>

<div style="padding-left: 3%;padding-right: 3%;">
    <table width="100%">
        <tr style="margin: 0;">
            <td style="width: 70%;"></td>
            <td align="left" style="width: 30%; font-size: 20px;">Billing Contact<br><hr><a href="https://designsowl.com/contact" style="color: #000;">https://designsowl.com/contact</a></td>
        </tr>
    </table>
</div>

<div style="padding-left: 3%;padding-right: 3%; position: absolute; bottom: 0;width: 100%; text-align: center;">
    <p style="font-size: 28px;">Thank you for your business!</p>
</div>
</body>