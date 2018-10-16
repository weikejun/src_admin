<?php

$deals = new Model_Project;
$deals = $deals->find();
$members = Model_Member::listAll();

foreach($deals as $deal) {
    foreach(['partner','manager','deal_manager','legal_person','finance_person'] as $pKey) {
        $person = $deal->getData($pKey);
        if (empty($person)) continue;
        $isSetting = is_numeric($person) ? true : false;
        foreach($members as $member) {
            if (strpos($member->mName, $person) !== false) {
                $deal->setDataMerge([$pKey => $member->mId]);
                $deal->save();
                $isSetting = true;
            }
        }
        echo $isSetting ? "" : "Project:$deal->mId|$pKey:$person\n";
    }
}

$companys = new Model_Company;
$companys = $companys->find(); 

foreach($companys as $company) {
    foreach(['partner','manager','filling_keeper','legal_person','finance_person'] as $pKey) {
        $person = $company->getData($pKey);
        if (empty($person)) continue;
        $isSetting = is_numeric($person) ? true : false;
        foreach($members as $member) {
            if (strpos($member->mName, $person) !== false) {
                $company->setDataMerge([$pKey => $member->mId]);
                $company->save();
                $isSetting = true;
            }
        }
        echo $isSetting ? "" : "Company:$company->mId|$pKey:$person\n";
    }
}
