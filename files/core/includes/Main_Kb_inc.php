<?php
if( preg_match( "/includes/" , $_SERVER["PHP_SELF"] ) ) { 
header('HTTP/1.0 404 Not Found');
exit;
}


$validateip = "Active";

if($validateip =='Active'){


class accounts
{
	private function createaccount($domain, $user, $pwd, $email, $disk = 0, $bw = 0, $package)
	{
		$whmParams = [];
		$whmParams['username'] = $user;
		$whmParams['domain'] = $domain;
		$whmParams['reseller'] = '1';
		$whmParams['password'] = $pwd;
		$whmParams['contactemail'] = $email;
		$whmParams['quota'] = $disk;
		$whmParams['bwlimit'] = $bw;
		error_log('package : ' . $package, 3, 'pakcgae.log');
		$whmParams['pkgname'] = $package;
		$result = $this->whmapi('createacct', $whmParams);
		$result = $result->result[0];

		if ($result->status != 1) {
			$response['error'] = 'Account Creation Failed<br />' . $result->statusmsg;
			return $response;
		}

		$whmParams = [];
		$whmParams['user'] = $user;
		$whmParams['owner'] = $_ENV['REMOTE_USER'];
		$result = $this->whmapi('modifyacct', $whmParams);
		sleep(15);
		$this->setacl($user);
	}

	private function setacl($user)
	{
		$whmParams = [];
		$whmParams['reseller'] = $user;
		$config = getFile('includes/config');
		$whmParams['acllist'] = $config['acl'];
		$result = $this->whmapi('setacls', $whmParams);
	}

	private function whmseller_a($domain)
	{
		if ($_ENV['REMOTE_USER'] == 'root') {
			return true;
		}

		$masters = [];

		if (file_exists('alphas/' . $_ENV['REMOTE_USER'])) {
			$alpData = getFile('alphas/' . $_ENV['REMOTE_USER']);
			$masters = array_filter(explode(',', $alpData['masters']));

			if (in_array($domain, $masters)) {
				return true;
			}
		}

		$masters[] = $_ENV['REMOTE_USER'];
		$resellers = [];

		foreach ($masters as $master) {
			$masterData = getFile('masters/' . $master);
			$resellers = array_merge($resellers, array_filter(explode(',', $masterData['resellers'])));
		}

		if (in_array($domain, $resellers)) {
			return true;
		}

		$whmParams = [];
		$whmParams['search'] = $domain;
		$whmParams['searchtype'] = 'user';
		$result = $this->whmapi('listaccts', $whmParams);

		if (in_array($result->acct[0]->owner, $resellers)) {
			return true;
		}

		return false;
	}

	public function setlimits($user, $domains, $disk, $bw, $dsos, $bwos)
	{
		$whmParams = [];
		$whmParams['user'] = $user;

		if (!is_numeric($domains)) {
			$whmParams['enable_account_limit'] = 0;
		}
		else{
			$whmParams['enable_account_limit'] = 1;
			$whmParams['account_limit'] = $domains;
		}
	

		if ($disk != '') {
			if (($disk == 'unlimited') || !is_numeric($disk)) {
				$whmParams['enable_resource_limits'] = 0;
			}
			else {
				$whmParams['enable_resource_limits'] = 1;
				$whmParams['diskspace_limit'] = $disk;

				if ($dsos) {
					$whmParams['enable_overselling_diskspace'] = 1;
				}
				else {
					$whmParams['enable_overselling_diskspace'] = 0;
				}
			}
		}

		if ($bw != '') {
			if (($bw == 'Unlimited') || !is_numeric($bw)) {
				if ($whmParams['enable_resource_limits'] == 0) {
					$whmParams['enable_resource_limits'] = 0;
				}
			}
			else {
				$whmParams['enable_resource_limits'] = 1;
				$whmParams['bandwidth_limit'] = $bw;

				if ($bwos) {
					$whmParams['enable_overselling_bandwidth'] = 1;
				}
				else {
					$whmParams['enable_overselling_bandwidth'] = 0;
				}
			}
		}

		$result = $this->whmapi('setresellerlimits', $whmParams);
	}

	public function getdomains()
	{
		$user = $_ENV['REMOTE_USER'];
		$totalDomains = $usedDomains = 'unlimited';

		if ($user != 'root') {
			$isAlpha = 0;

			if (file_exists('alphas/' . $user)) {
				$isAlpha = 1;
				$alpData = getFile('alphas/' . $user);
				$totalDomains = $alpData['domainsallowed'];
				$usedDomains = 0;
				$masters = array_filter(explode(',', $alpData['masters']));

				foreach ($masters as $master) {
					if ($master == $user) {
						continue;
					}

					$masData = getFile('masters/' . $master);
					$usedDomains += intval($masData['domainsallowed']);
				}
			}

			$masData = getFile('masters/' . $user);

			if ($isAlpha == 0) {
				$totalDomains = $masData['domainsallowed'];
				$usedDomains = 0;
			}

			$resellers = array_filter(explode(',', $masData['resellers']));

			foreach ($resellers as $reseller) {
				$whmParams = [];
				$whmParams['user'] = $reseller;
				$result = $this->whmapi('acctcounts', $whmParams);
				$usedDomains += $result->reseller->limit;
			}
		}

		return [$totalDomains, $usedDomains];
	}

	public function createreseller($domain, $user, $pwd, $email, $domains, $disk, $bw, $dsos, $bwos, $master, $upgrade = 0, $package = '', $is_reseller = 0)
	{
		if (empty($master)) {
			$master = $_ENV['REMOTE_USER'];
		}

		if ($master != 'null') {
			$masterData = getFile('masters/' . $master);
			$resellersCount = count(array_filter(explode(',', $masterData['resellers'])));
			if (!is_numeric($masterData['resellersallowed']) || ($resellersCount < $masterData['resellersallowed'])) {
				$domainLimit = $this->getdomains();

				if (is_numeric($domainLimit[0])) {
					if ($domains <= 0) {
						$response['error'] = 'Enter a valid Max Domains';
						return $response;
					}

					if (!is_numeric($domains)) {
						$response['error'] = 'You can allocate upto ' . ($domainLimit[0] - $domainLimit[1]) . ' domains for this account';
						return $response;
					}

					if (($domainLimit[0] - intval($domainLimit[1])) < $domains) {
						$response['error'] = 'You can allocate upto ' . ($domainLimit[0] - $domainLimit[1]) . ' domains for this account';
						return $response;
					}
				}
			}
			else {
				$response['error'] = 'You cannot create more than ' . $masterData['resellersallowed'] . ' resellers';
				return $response;
			}
		}

		if ($domain == '') {
			$response['error'] = 'Enter a valid domain name';
			return $response;
		}

		if ($user == '') {
			$user = preg_replace('/[^A-Za-z0-9\\-]/', '', $domain);
			$user = substr($user, 0, 8);
		}
		if (empty($pwd) || (strlen($pwd) < 5)) {
			$pwd = substr(uniqid(), 0, 12);
		}

		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$email = '';
		}

		if (!$upgrade) {
			$result = $this->createaccount($domain, $user, $pwd, $email, $disk, $bw, $package);

			if ($result['error'] != '') {
				return $result;
			}
		}
		else {
			$whmParams = [];
			$whmParams['user'] = $user;
			$whmParams['makeowner'] = '1';
			$result = $this->whmapi('setupreseller', $whmParams);
			$this->setacl($user);
		}

		if ($master != 'null') {
			$explode = explode(',', $masterData['resellers']);
			$explode[] = $user;
			$masterData['resellers'] = implode(',', array_filter(array_unique($explode)));
			$this->updatemaster($masterData['username'], $masterData);
		}

		$this->setlimits($user, $domains, $disk, $bw, $dsos, $bwos);
		$whmParams2 = [];
		$whmParams2['user'] = $user;
		if(is_numeric($domains)){
		$whmParams2['enable_account_limit'] = 1;
		$whmParams2['account_limit'] = $domains;
	}else{
				$whmParams2['enable_account_limit'] = 0;

	}

		if ($is_reseller == 1) {
			$result2 = $this->whmapi('setresellerlimits', $whmParams2);
		}

		$response['user'] = $user;
		$response['response'] = 'Account created successfully.' . "\r\n\t\t\t\t" . '<br> <strong>Domain Name</strong> : ' . $domain . "\r\n\t\t\t\t" . '<br> <strong>Username</strong> : ' . $user . "\r\n\t\t\t\t" . '<br> <strong>Password</strong> : ' . $pwd . "\r\n\t\t\t";
		return $response;
	}

	public function whmseller_b($user, $action)
	{
		$master = $_ENV['REMOTE_USER'];

		if ($_ENV['REMOTE_USER'] != '') {
			if (!$this->whmseller_a($user)) {
				$response['error'] = 'You do not have access to target domain';
				return $response;
			}
		}

		if ($user == $_ENV['REMOTE_USER']) {
			$response['error'] = 'You cannot do this function for this account';
			return $response;
		}

		if (in_array($action, ['suspend', 'unsuspend', 'terminate', 'removers'])) {
			$whmParams = [];
			$whmParams['user'] = $user;

			if ($action == 'suspend') {
				$api = 'suspendreseller';
				$whmParams['disallow'] = 1;
				$response['response'] = 'Reseller has been Suspended';
			}
			else if ($action == 'unsuspend') {
				$api = 'unsuspendreseller';
				$response['response'] = 'Reseller has been Unsuspended';
			}
			else if ($action == 'removers') {
				$api = 'unsetupreseller';
				$response['response'] = 'Reseller permission removed';
			}
			else if ($action == 'terminate') {
				$whmParams['reseller'] = $user;
				$whmParams['terminatereseller'] = 1;
				$whmParams['verify'] = 'I understand this will irrevocably remove all the accounts owned by the reseller ' . $user;
				$api = 'terminatereseller';
				$response['response'] = 'Reseller has been terminated';
			}

			$result = $this->whmapi($api, $whmParams);
			if (($action == 'removers') && ($master != 'root')) {
				$whmParams['owner'] = $master;
				$this->whmapi('modifyacct', $whmParams);
				$whmParams = [];
				$whmParams['search'] = $user;
				$whmParams['searchtype'] = 'owner';
				$result = $this->whmapi('listaccts', $whmParams);

				foreach ($result->acct as $accounts) {
					$whmParams = [];
					$whmParams['user'] = $accounts->user;
					$whmParams['owner'] = $master;
					$this->whmapi('modifyacct', $whmParams);
				}
			}
			if ((($action == 'terminate') || ($action == 'removers')) && ($master != 'root')) {
				$masData = getFile('masters/' . $master);
				$masData['resellers'] = implode(',', array_filter(array_unique(explode(',', str_ireplace($user, '', $masData['resellers'])))));
				$this->updatemaster($master, $masData);
			}

			return $response;
		}
	}

	public function whmseller_d($user, $domains, $disk, $bw, $dsos, $bwos)
	{
		if (isset($domains) && ($domains != '')) {
			$domainLimit = $this->getdomains();

			if (is_numeric($domainLimit[0])) {
				if ($domains <= 0) {
					$response['error'] = 'Enter a valid Max Domains';
					return $response;
				}

				if (!is_numeric($domains)) {
					$response['error'] = 'You can allocate upto ' . ($domainLimit[0] - $domainLimit[1]) . ' domains for this account';
					return $response;
				}

				$whmParams = [];
				$whmParams['user'] = $user;
				$result = $this->whmapi('acctcounts', $whmParams);
				$domainsRem = ($domainLimit[0] - intval($domainLimit[1])) + intval($result->reseller->limit);

				if ($domainsRem < $domains) {
					$response['error'] = 'You can allocate upto ' . $domainsRem . ' domains for this account';
					return $response;
				}
			}
		}

		$response = $this->setlimits($user, $domains, $disk, $bw, $dsos, $bwos);
		return $response;
	}

	public function upgradereseller($user, $domains)
	{
			if (!$this->whmseller_a($user)) {
			$response['error'] = 'You do not have access to target domain';
			return $response;
		
		}

	
		$whmParams = [];
		$whmParams['user'] = $user;
		$result = $this->whmapi('accountsummary', $whmParams);
		$domain = $result->acct[0]->domain;
		$result = $this->createreseller($domain, $user, '', '', $domains, '', '', '', '', $_ENV['REMOTE_USER'], 1);

		if ($result['error'] != '') {
			$response['error'] = $result['error'];
		}
		else {
			$whmParams2 = [];
			$whmParams2['user'] = $user;
			$whmParams2['owner'] = $_ENV['REMOTE_USER'];
			$result2 = $this->whmapi('modifyacct', $whmParams2);
			$whmParams3 = [];
			$whmParams3['user'] = $user;
			if(is_numeric($domains)){
			$whmParams3['enable_account_limit'] = 1;
			$whmParams3['account_limit'] = $domains;
		}
			else{
				$whmParams3['enable_account_limit'] = 0;
			}
			$result3 = $this->whmapi('setresellerlimits', $whmParams3);
			$response['response'] = 'cPanel has been upgraded to Reseller';
		}

		return $response;
	}

	public function createmaster($domain = NULL, $user = NULL, $pwd = NULL, $email = NULL, $maxdomains = NULL, $resellers = NULL, $disk = NULL, $bw = NULL, $dsos = NULL, $bwos = NULL, $upgrade = NULL, $alpha = NULL, $package = NULL, $account_limit = 0, $cpanel_to_reseller = 0)
	{
		if (empty($alpha)) {
			$alpha = $_ENV['REMOTE_USER'];
		}

		if ($alpha != 'null') {
			$alpData = getFile('alphas/' . $alpha);
			$mastersCount = count(array_filter(explode(',', $alpData['masters'])));
			if (!is_numeric($alpData['mastersallowed']) || ($mastersCount < $alpData['mastersallowed'])) {
				$domainLimit = $this->getdomains();

				if (is_numeric($domainLimit[0])) {
					if ($maxdomains <= 0) {
						$response['error'] = 'Enter a valid Max Domains';
						return $response;
					}

					if ($upgrade == 1) {
						$whmParams = [];
						$whmParams['user'] = $user;
						$result = $this->whmapi('acctcounts', $whmParams);
						$allowedDomains = ($domainLimit[0] - intval($domainLimit[1])) + $result->reseller->limit;
					}
					else {
						$allowedDomains = $domainLimit[0] - intval($domainLimit[1]);
					}

					if (!is_numeric($maxdomains)) {
						$response['error'] = 'You can allocate upto ' . $allowedDomains . ' domains for this account';
						return $response;
					}

					if ($allowedDomains < $maxdomains) {
						$response['error'] = 'You can allocate upto ' . $allowedDomains . ' domains for this account';
						return $response;
					}
				}
			}
			else {
				$response['error'] = 'You cannot create more than ' . $alpData['mastersallowed'] . ' Masters';
				return $response;
			}
		}

		if ($upgrade != 1) {
			$resellerDomains = 'unlimited';

			if (is_numeric($maxdomains)) {
				$resellerDomains = intval($maxdomains * 0.1);
			}

			error_log('package 3 : ' . $package, 3, 'pakcgae.log');
			$response = $this->createreseller($domain, $user, $pwd, $email, $maxdomains, $disk, $bw, $dsos, $bwos, 'null', '0', $package, $account_limit);

			if ($response['error']) {
				return $response;
			}

			if ($user == '') {
				$user = $response['user'];
			}
		}
		else {
			$whmParams2 = [];
			$whmParams2['user'] = $user;
			$whmParams2['owner'] = 'root';
			$result2 = $this->whmapi('modifyacct', $whmParams2);
			$this->setacl($user);
			$response['response'] = 'Reseller has been upgraded to Master Reseller';
		}
		if (($domain == '') || ($user == '')) {
			$response['error'] = 'User and Domain cannot be empty';
		}
		else {
			if (!is_numeric($resellers)) {
				$resellers = 'unlimited';
			}

			$masterData = [];
			$masterData['domainname'] = strtolower($domain);
			$masterData['username'] = strtolower($user);
			$masterData['resellersallowed'] = $resellers;
			$masterData['domainsallowed'] = $maxdomains;
			$masterData['resellers'] = $user;
			$masterData['status'] = 'active';
			file_put_contents('masters/' . $user, buildFile($masterData));

			if ($_ENV['REMOTE_USER'] != 'root') {
				$alpData = getFile('alphas/' . $_ENV['REMOTE_USER']);
				$explode = explode(',', $alpData['masters']);
				$explode[] = $user;
				$alpData['masters'] = implode(',', array_filter(array_unique($explode)));
				$this->whmseller_c($alpData['username'], $alpData);
			}

			$config = getFile('includes/config');
			$autoAlpha = array_filter(explode(',', $config['autoalpha']));

			if (in_array($_ENV['REMOTE_USER'], $autoAlpha)) {
				$this->whmseller_e($user);
			}
		}

		return $response;
	}

	public function whmseller_f($user, $action)
	{
		if ($_ENV['REMOTE_USER'] != '') {
			if (!$this->whmseller_a($user)) {
				$response['error'] = 'You do not have access to target domain';
				return $response;
			}
		}

		if ($user == $_ENV['REMOTE_USER']) {
			$response['error'] = 'You cannot do this function for this account';
			return $response;
		}

		if (in_array($action, ['suspend', 'unsuspend', 'terminate', 'removems'])) {
			$masData = getFile('masters/' . $user);
			$resellers = array_filter(explode(',', $masData['resellers']));
			$resellers = array_map('trim', $resellers);

			if ($action == 'suspend') {
				foreach ($resellers as $reseller) {
					$this->whmseller_b($reseller, 'suspend');
				}

				$this->updatemaster($user, ['status' => 'suspended']);
				$response['response'] = 'Master Reseller has been suspended';
			}
			else if ($action == 'unsuspend') {
				foreach ($resellers as $reseller) {
					$this->whmseller_b($reseller, 'unsuspend');
				}

				$this->updatemaster($user, ['status' => 'active']);
				$response['response'] = 'Master Reseller has been unsuspended';
			}
			else if ($action == 'removems') {
				if (($key = array_search($user, $resellers)) !== false) {
					unset($resellers[$key]);
				}

				if (0 < count($resellers)) {
					$response['error'] = 'Move or Terminate Master\'s resellers before removing Master Reseller permission';
				}
				else {
					unlink('masters/' . $user);
					$response['response'] = 'Master Reseller Permission has been removed.';
				}
			}
			else if ($action == 'terminate') {
				foreach ($resellers as $reseller) {
					$this->whmseller_b($reseller, 'terminate');
					$response['response'] = 'Master Reseller has been terminated';
				}

				unlink('masters/' . $user);
			}
			if (($response['error'] == '') && ($_ENV['REMOTE_USER'] != 'root') && (($action == 'terminate') || ($action == 'removems'))) {
				$alpData = getFile('alphas/' . $_ENV['REMOTE_USER']);
				$alpData['masters'] = implode(',', array_filter(array_unique(explode(',', str_ireplace($user, '', $alpData['masters'])))));
				$this->whmseller_c($alpData['username'], $alpData);

				if ($action == 'removems') {
					$masData = getFile('masters/' . $_ENV['REMOTE_USER']);
					$resellers = array_filter(explode(',', $masData['resellers']));
					$resellers[] = $user;
					$masData['resellers'] = implode(',', array_filter(array_unique($resellers)));
					$this->updatemaster($masData['username'], $masData);
				}
			}

			return $response;
		}
	}

	public function updatemaster($user, $data)
	{
		if (!$this->whmseller_a($user)) {
			$response['error'] = 'You do not have access to target domain';
			return $response;
		}

		$masData = getFile('masters/' . $user);

		if (isset($data['domainsallowed'])) {
			$domainLimit = $this->getdomains();

			if (is_numeric($domainLimit[0])) {
				if ($data['domainsallowed'] <= 0) {
					$response['error'] = 'Enter a valid Max Domains';
					return $response;
				}

				if (!is_numeric($data['domainsallowed'])) {
					$response['error'] = 'You can allocate upto ' . ($domainLimit[0] - $domainLimit[1]) . ' domains for this account';
					return $response;
				}

				if ($user == $_ENV['REMOTE_USER']) {
					$domainsRem = $domainLimit[0];
				}
				else {
					$domainsRem = ($domainLimit[0] - intval($domainLimit[1])) + $masData['domainsallowed'];
				}

				if ($domainsRem < $data['domainsallowed']) {
					$response['error'] = 'You can allocate upto ' . $domainsRem . ' domains for this account';
					return $response;
				}
			}
		}

		$fields = ['domainname', 'username', 'resellersallowed', 'domainsallowed', 'status', 'resellers', 'pkgname'];

		foreach ($data as $key => $value) {
			if (in_array($key, $fields)) {
				$masData[$key] = strtolower($value);
			}
		}

		file_put_contents('masters/' . $user, buildFile($masData));
		$response['response'] = 'Master reseller has been updated';
		return $response;
	}

	public function upgrademaster($user, $resellers, $maxdomains, $cpanel_to_reseller = 0)
	{
		if (!$this->whmseller_a($user)) {
			$response['error'] = 'You do not have access to target domain';
			return $response;
		}

		$whmParams = [];
		$whmParams['user'] = $user;
		$result = $this->whmapi('accountsummary', $whmParams);
		$domain = $result->acct[0]->domain;
		$result = $this->createmaster($domain, $user, '', '', $maxdomains, $resellers, '', '', '', '', 1, $cpanel_to_reseller);

		if ($result['error'] == '') {
			$masters = glob('masters/*');

			foreach ($masters as $master) {
				$masData = getFile($master);
				if (($user != $masData['username']) && (stripos($masData['resellers'], $user) !== false)) {
					$masData['resellers'] = implode(',', array_filter(array_unique(explode(',', str_ireplace($user, '', $masData['resellers'])))));
					unset($masData['domainsallowed']);
					$res = $this->updatemaster($masData['username'], $masData);
					break;
				}
			}
		}

		return $result;
	}

	public function whmseller_g($domain = NULL, $user = NULL, $pwd = NULL, $email = NULL, $maxdomains = NULL, $masters = NULL, $disk = NULL, $bw = NULL, $dsos = NULL, $bwos = NULL, $upgrade = NULL, $package = NULL)
	{
		if ($upgrade != 1) {
			$masterDomains = $maxdomains;
			error_log('package 5 : ' . $package, 3, 'alpha_whmcs3.log');
			$response = $this->createmaster($domain, $user, $pwd, $email, $masterDomains, 'unlimited', $disk, $bw, $dsos, $bwos, 0, 'null', $package, $masters);

			if ($response['error']) {
				return $response;
			}

			if ($user == '') {
				$user = $response['user'];
			}
		}
		else {
			$response['response'] = 'Master has been upgraded to Alpha Reseller';
		}
		if (($domain == '') || ($user == '')) {
			$response['error'] = 'User and Domain cannot be empty';
		}
		else {
			if (!is_numeric($masters)) {
				$masters = 'unlimited';
			}

			$alphaData = [];
			$alphaData['domainname'] = strtolower($domain);
			$alphaData['username'] = strtolower($user);
			$alphaData['mastersallowed'] = $masters;
			$alphaData['domainsallowed'] = $maxdomains;
			$alphaData['masters'] = $user;
			$alphaData['status'] = 'active';
			file_put_contents('alphas/' . $user, buildFile($alphaData));
		}

		return $response;
	}

	public function whmseller_h($user, $action)
	{
		if (in_array($action, ['suspend', 'unsuspend', 'terminate', 'removeal'])) {
			$alpData = getFile('alphas/' . $user);
			$masters = array_filter(explode(',', $alpData['masters']));
			$masters = array_map('trim', $masters);

			if ($action == 'suspend') {
				foreach ($masters as $master) {
					$this->whmseller_f($master, 'suspend');
				}

				$this->whmseller_c($user, ['status' => 'suspended']);
				$response['response'] = 'Alpha Reseller has been suspended';
			}
			else if ($action == 'unsuspend') {
				foreach ($masters as $master) {
					$this->whmseller_f($master, 'unsuspend');
				}

				$this->whmseller_c($user, ['status' => 'active']);
				$response['response'] = 'Alpha Reseller has been unsuspended';
			}
			else if ($action == 'removeal') {
				if (($key = array_search($user, $masters)) !== false) {
					unset($masters[$key]);
				}

				if (0 < count($masters)) {
					$response['error'] = 'Move or Terminate Masters before removing Alpha Reseller permission';
				}
				else {
					unlink('alphas/' . $user);
					$response['response'] = 'Alpha Reseller Permission has been removed.';
				}
			}
			else if ($action == 'terminate') {
				foreach ($masters as $master) {
					$this->whmseller_f($master, 'terminate');
					$response['response'] = 'Alpha Reseller has been terminated';
				}

				unlink('alphas/' . $user);
			}

			return $response;
		}
	}

	public function whmseller_c($user, $data)
	{
		$alpData = getFile('alphas/' . $user);
		$fields = ['domainname', 'username', 'domainsallowed', 'mastersallowed', 'status', 'masters'];

		foreach ($data as $key => $value) {
			if (in_array($key, $fields)) {
				$alpData[$key] = strtolower($value);
			}
		}

		file_put_contents('alphas/' . $user, buildFile($alpData));
		$response['response'] = 'Alpha reseller has been updated';
		return $response;
	}

	public function whmseller_e($user, $masters, $maxdomain)
	{
		$whmParams = [];
		$whmParams['user'] = $user;
		$result = $this->whmapi('accountsummary', $whmParams);
		$domain = $result->acct[0]->domain;
		$result = $this->whmseller_g($domain, $user, '', '', $maxdomain, $masters, '', '', '', '', 1);

		if ($result['error'] == '') {
			$aplhas = glob('alphas/*');

			foreach ($aplhas as $aplha) {
				$alpData = getFile($aplha);
				if (($user != $alpData['username']) && (stripos($alpData['masters'], $user) !== false)) {
					$alpData['masters'] = implode(',', array_filter(array_unique(explode(',', str_ireplace($user, '', $alpData['masters'])))));
					$res = $this->whmseller_c($alpData['username'], $alpData);
					break;
				}
			}
		}

		return $result;
	}

	public function changepwd($user, $pwd)
	{
		if (!$this->whmseller_a($user)) {
			$response['error'] = 'You do not have access to target domain';
			return $response;
		}

		$whmParams = [];
		$whmParams['user'] = $user;
		$whmParams['pass'] = $pwd;
		$result = $this->whmapi('passwd', $whmParams);

		if ($result->passwd[0]->status != 1) {
			$response['error'] = $result->passwd[0]->statusmsg;
			return $response;
		}

		$response['response'] = 'Password change successfull';
		return $response;
	}

	public function whmapi2()
	{
		$whmusername = $_ENV['REMOTE_USER'];
		$whmpassword = $_ENV['REMOTE_PASSWORD'];
		$query = 'https://127.0.0.1:2087/json-api/listpkgs?api.version=1';
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$header[0] = 'Authorization: Basic ' . base64_encode($whmusername . ':' . $whmpassword) . "\n\r";
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($curl, CURLOPT_URL, $query);
		$result = curl_exec($curl);

		if (!$result) {
			error_log('curl_exec threw error "' . curl_error($curl) . '" for ' . $query);
		}

		curl_close($curl);
		return json_decode($result);
	}

	public function whmapi($function = NULL, $params = NULL)
	{
		$whmusername = 'root';

		if ($function == 'listpkgs') {
			$whmusername = $_ENV['REMOTE_USER'];
			return $this->whmapi2();
		}

		$whmhash = $this->gethash();
		$query = 'https://127.0.0.1:2087/json-api/' . $function;
		$query .= '?' . http_build_query($params);
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$header[0] = 'Authorization: WHM ' . $whmusername . ':' . preg_replace('\'(' . "\r" . '|' . "\n" . ')\'', '', $whmhash);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($curl, CURLOPT_URL, $query);
		$result = curl_exec($curl);
		curl_close($curl);
				return json_decode($result);
	}

	private function gethash()
	{
		if (!file_exists('/root/.api') && ($_ENV['REMOTE_USER'] == 'root')) {
			shell_exec('touch /root/.api');
			$output=shell_exec('whmapi1 api_token_create token_name=whmseller acl-1=all');
			$oparray = preg_split('/\s+/', trim($output));
			file_put_contents('/root/.api',$oparray[12]);
			
		}
		$getapi = file_get_contents("/root/.api");
		return $getapi;	
	
	}
}
	}



}

