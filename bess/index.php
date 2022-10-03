<!doctype html>
<html lang="en" ng-app="batterySavingsApp">
<head>
  <meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Battery Savings Calculator</title>
    <script src="../../js/jquery.js"></script>
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
    <link href="../../css/simple-sidebar.css" rel="stylesheet">
    <link rel='stylesheet' href='../../css/page.css' type='text/css' />	 	
    <link rel="stylesheet" href="css/app.css">
    <script src="lib/angular/angular.js"></script>
    <script src="js/controllers.js"></script>
</head>
<body ng-controller="BatterySavingsCtrl">
<div id='wrapper'>
<div id='sidebar-wrapper' class='hidden-print'>
<div id='sidebar'>
<div align='center'>


<div class='container'>

</p><h2>Basic parameters</h2>
<p>Update the following parameters to calculate your maximum savings from adding battery storage to your PV system. The values entered already are plausible defaults for a 4 kWp system in Cambridge and average household electricity use.</p>

<table class='spaced'>
<tr><td colspan='2'><hr></td></tr>
  <tr><td>PV / Solar Generation (kWh/year)</td>
    <td><input type='number' ng-model='generation' ng-change='calc()'></td></tr>

<tr><td colspan='2'><hr/>This parameter is how much electricity you take from the grid each year. You should find this on your bill somewhere. Households vary. For a medium household your 'true usage' (see below) could be 3200 kWh/year. However, since you probably use at least some of the electricity from your panels already your actual bill should be a bit less than this.<br/><br/>
The price you pay for electricity should also be on your bill. 14p/kWh is what I am paying at the moment  on a green tariff.</td></tr>
  <tr><td>Electricity bill (kWh/year)</td>
    <td><input type='number' ng-model='bill' ng-change='calcTrueBill()'></td></tr>

  <tr><td>Electricity price (p/kWh)</td>
    <td><input type='number' ng-model='price' ng-change='calc()' min='1' max='100' step='1'></td></tr>
<tr><td colspan='2'><hr/>Your self consumption rate is how much of your own power you are using already. You will almost certainly be using some. If you are  in during the day and trying hard to use your own power it could be 40% or even more. If you are mostly out of the house it would be much less. The average is probably 25% to 30%.</td></tr>
  <tr><td>Self consumption (%)</td>
    <td><input type='number' ng-model='selfConsumptionPC' ng-change='calcTrueBill()' min='0' max='100'></td></tr>
<tr><td colspan='2'><hr/>Your 'true usage' is how much electricity you actually use, including your self consumption as well as the amount you take from the grid. You can enter either this or your self consumption rate and this tool will adjust the other one.</td></tr>
  <tr><td>True usage (kWh/year)</td>
    <td><input type='number' ng-model='trueBill' ng-change='calcSelfConsumption()'></td>
    </tr>
</table>
<h2>Calculate maximum savings by summer/winter</h2>
<p>Your bill savings are limited both by the amount of electricity generated and the amount of electricity you use. In the winter you are likely to be limited by how much you generate and in the summer you are likely to be limited by how much you use. Here is a very rough calculation of what that could mean for you. It assumes that your true electricity usage is the same throughout the year.  In practice it is likely to be higher in winter than in summer so this will tend to overestimate your savings.</p>
<p>This rough calculation splits the year into two. 2/3 of your PV electricity is generated during April to September.</p>
<p>In practice the size of the battery is also important. This calculation assumes that your battery is 'big enough'.</p>

<table class='table'>
<tr><th>&nbsp;</th>
  <th>Generation (kWh)</th>
  <th>Usage (kWh)</th>
  <th>Max. savings (kWh)</th>
<tr><td>Apr-Sep (67%)</td>
  <td align='right'>{{summerGeneration|number:0}}</td>
  <td align='right'>{{summerUsage|number:0}}</td>
  <td align='right'>{{maxSummerSavings|number:0}}</td>
<tr><td>Jan-Mar, Oct-Dec (33%)</td>
  <td align='right'>{{winterGeneration|number:0}}</td>
  <td align='right'>{{winterUsage|number:0}}</td>
  <td align='right'>{{maxWinterSavings|number:0}}</td>
<tr><td>Total</td>
  <td align='right'>{{generation|number:0}}</td>
  <td align='right'>{{trueBill|number:0}}</td>
  <td align='right'>{{totalSavings|number:0}}</td>
</table>

<table class='spaced'>
  <tr><td>Maximum savings from PV (kWh/year)</td>
    <td align='right'>{{totalSavings|number:0}}</td></tr>
  <tr><td>Current savings from self consumption (kWh/year)</td>
    <td align='right'>{{currentSavings|number:0}}</td></tr>
  <tr><td>Maximum additional savings from battery (kWh/year)</td>
    <td align='right'>{{netSavings|number:0}}</td></tr>
  <tr><td>Maximum self consumption with battery </td>
    <td align='right'>{{finalSelfConsumption|PC}}</td></tr>
  <tr><td>Maximum additional savings from battery (£/year)</td>
    <td align='right'><mark>{{netSavingsGBP|GBP}}</mark></td></tr>
</table>

<h2>Adjustments for reduced Feed in Tariffs payment</h2>
<p> If the battery is going to be DC connected (i.e. to your inverter) then when the battery is used it reduces the amount of electricity recorded by your generation meter slightly, depending on the efficiency of the charge/discharge cycle. This is normally between 80% and 95%.</p>
<p>If the battery is going to be AC connected, enter 100% for no loss.</p>
<p>The Feed in Tariff comes in two parts. The generation tariff is paid for everything you generate. The rate you get depends on when you installed the system. Check your most recent Feed in Tariff payment information for your current rates. The export tariff is paid on half of what you generate - it is assumed that you use half and export half. So the total Feed in Tariff is the generation tariff plus half the export tariff.</p>
<table class='spaced'>
  <tr><td>Total feed in tariff (p/kWh)<br/> Generation tariff + half the export tariff.</td>
    <td><input type='number' ng-model='FiTs' ng-change='calc()' max='60'> </td></tr>
  <tr><td>Battery Efficiency (%)<br/>Typically 80% to 95%.</td>
    <td><input type='number' ng-model='efficiencyPC' ng-change='calc()' min='60' max='100'></td>
  <tr><td>Reduction in Feed in Tariffs payment (p/kWh)</td>
    <td>{{FiTsLoss|number:1}}</td></tr>
  <tr><td>Reduction factor</td>
  <td>{{FiTsFactor|PC}}</td></tr>
  <tr><td>Adjusted maximum savings £/year</td>
    <td><mark>{{FinalSavingsGBP |GBP}}</mark></td></tr>
</table>
<h2>Calculate minumum payback time</h2>
<p>Calculate the overall minimum payback time based on the cost of
your battery system. Ideally your battery should be guaranteed for
this long. System costs vary. A DC connected system may be cheaper
then an AC connected system but even then £3200 is probably at the low
end. Always get at least two quotes, preferably three.</p>

<table class='spaced'>
  <tr><td>Cost of system £</td><td><input type='number' ng-model='cost' ng-change='calc()'></td></tr>
  <tr><td>Payback time (years)</td><td>{{paybackTime|number:0}}</td></tr>

</table>
<hr>

</body>
</div>
<hr/>
</div><!--pagebody-->
</div>

    <!-- Bootstrap Core JavaScript -->
    <script src="../../js/bootstrap.min.js"></script>

    <!-- Menu Toggle Script -->
    <script>
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
    </script>
</body>
</html>