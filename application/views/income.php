<form action="<?= base_url() ?>income/update" method="post">
Coin:<input type="number" name="mycoin"/><br>
Wallet: <select name="wallet_type">
			<option value="Vodafone">Vodafone</option>
			<option value="Pay U Money">Pay U Money</option>
			<option value="Airtel Money">Airtel Money</option>
			<option value="Paytm">Paytm</option>
		</select><br>
Mobile No:<input type="number" name="mob_no"/><br>
Bank Name:<input type="text" name="bank_name"/><br>
Acc No:<input type="text" name="acc_no"/><br>
Account Holder:<input type="text" name="acc_holder"/><br>
<input type="submit" name="sub" value="Submit" />
</form>
