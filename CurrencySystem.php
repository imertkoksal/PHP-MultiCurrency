<?php
class KoksalBankStyleCurrency__System {
private array $balances = [];
private array $currencies = [

    "USD"=>[
        "symbol"=>"$",
        "position"=>"left",
        "main"=>["en"=>"dollar","tr"=>"dolar","de"=>"Dollar","ja"=>"ドル"],
        "fraction"=>["en"=>"cent","tr"=>"sent","de"=>"Cent","ja"=>"セント"]
    ],

    "EUR"=>[
        "symbol"=>"€",
        "position"=>"left",
        "main"=>["en"=>"euro","tr"=>"euro","de"=>"Euro","ja"=>"ユーロ"],
        "fraction"=>["en"=>"cent","tr"=>"sent","de"=>"Cent","ja"=>"セント"]
    ],

    "JPY"=>[
        "symbol"=>"¥",
        "position"=>"left",
        "main"=>["en"=>"yen","tr"=>"yen","de"=>"Yen","ja"=>"円"],
        "fraction"=>["en"=>"sen","tr"=>"sen","de"=>"Sen","ja"=>"銭"]
    ],

    "GBP"=>[
        "symbol"=>"£",
        "position"=>"left",
        "main"=>["en"=>"pound","tr"=>"sterlin","de"=>"Pfund","ja"=>"ポンド"],
        "fraction"=>["en"=>"pence","tr"=>"pence","de"=>"Pence","ja"=>"ペンス"]
    ],

    "TRY"=>[
        "symbol"=>"₺",
        "position"=>"right",
        "main"=>["en"=>"lira","tr"=>"lira","de"=>"Lira","ja"=>"リラ"],
        "fraction"=>["en"=>"kuruş","tr"=>"kuruş","de"=>"Kurus","ja"=>"クルシュ"]
    ],

    "CHF"=>[
        "symbol"=>"CHF",
        "position"=>"left",
        "main"=>["en"=>"franc","tr"=>"frank","de"=>"Franken","ja"=>"フラン"],
        "fraction"=>["en"=>"centime","tr"=>"centime","de"=>"Rappen","ja"=>"ラッペン"]
    ],

    "CNY"=>[
        "symbol"=>"¥",
        "position"=>"left",
        "main"=>["en"=>"yuan","tr"=>"yuan","de"=>"Yuan","ja"=>"元"],
        "fraction"=>["en"=>"fen","tr"=>"fen","de"=>"Fen","ja"=>"分"]
    ],

    "SEK"=>[
        "symbol"=>"kr",
        "position"=>"right",
        "main"=>["en"=>"krona","tr"=>"krona","de"=>"Krone","ja"=>"クローナ"],
        "fraction"=>["en"=>"öre","tr"=>"öre","de"=>"Öre","ja"=>"オーレ"]
    ],

];

public function setBalance($user,$amount,$currency){
    $this->balances[$user][$currency]=$amount;
}

public function getBalance($user,$currency){
    return $this->balances[$user][$currency]??0;
}

public function format($amount,$currency,$locale){
    $c=$this->currencies[$currency];
    if(in_array($locale,["tr","de"]))
        $number=number_format($amount,2,",",".");
    else
        $number=number_format($amount,2,".",",");
    return $c["position"]=="left"
        ? $c["symbol"].$number
        : $number.$c["symbol"];
}

public function text($amount,$currency,$locale){
    $c=$this->currencies[$currency];
    $whole=floor($amount);
    $fraction=round(($amount-$whole)*100);

    $main=$c["main"][$locale];
    $sub=$c["fraction"][$locale];

    $words=$this->number($whole,$locale)." ".$main;

    if($fraction>0){
        $and = match($locale){
            "en"=>" and ",
            "de"=>" und ",
            default=>" "
        };

        $words.=$and.$this->number($fraction,$locale)." ".$sub;
    }
    return ucfirst($words);
}

private function number($n,$locale){

    return match($locale){
        "tr"=>$this->tr($n),
        "en"=>$this->en($n),
        "de"=>$this->de($n),
        "ja"=>$this->ja($n),
        default=>$n
    };
}

private function tr($n){
    $b=['','bir','iki','üç','dört','beş','altı','yedi','sekiz','dokuz'];
    $o=['','on','yirmi','otuz','kırk','elli','altmış','yetmiş','seksen','doksan'];

    $s='';

    if($n>=1000){
        $t=intval($n/1000);
        if($t>1)$s.=$this->tr($t).' ';
        $s.='bin ';
        $n%=1000;
    }

    if($n>=100){
        $y=intval($n/100);
        if($y>1)$s.=$b[$y].' ';
        $s.='yüz ';
        $n%=100;
    }

    if($n>=10){
        $s.=$o[intval($n/10)].' ';
        $n%=10;
    }
    if($n>0)$s.=$b[$n];
    return trim($s);
}

private function en($n){
    $a=["","one","two","three","four","five","six","seven","eight","nine"];
    $b=["ten","eleven","twelve","thirteen","fourteen","fifteen","sixteen","seventeen","eighteen","nineteen"];
    $c=["","","twenty","thirty","forty","fifty","sixty","seventy","eighty","ninety"];

    if($n<10)return $a[$n];
    if($n<20)return $b[$n-10];
    if($n<100)return $c[intval($n/10)].($n%10?"-".$a[$n%10]:"");
    if($n<1000)return $a[intval($n/100)]." hundred".($n%100?" ".$this->en($n%100):"");
    if($n<1000000)return $this->en(intval($n/1000))." thousand".($n%1000?" ".$this->en($n%1000):"");
    return $n;
}

private function de($n){
    $a=["","ein","zwei","drei","vier","fünf","sechs","sieben","acht","neun"];
    $b=["zehn","elf","zwölf","dreizehn","vierzehn","fünfzehn","sechzehn","siebzehn","achtzehn","neunzehn"];
    $c=["","","zwanzig","dreißig","vierzig","fünfzig","sechzig","siebzig","achtzig","neunzig"];

    if($n<10)return $a[$n];
    if($n<20)return $b[$n-10];
    if($n<100){
        $t=intval($n/10);
        $o=$n%10;
        return $o?$a[$o]."und".$c[$t]:$c[$t];
    }

    if($n<1000){
        $h=intval($n/100);
        return $a[$h]."hundert".($n%100?$this->de($n%100):"");
    }

    if($n<1000000){
        $t=intval($n/1000);
        return $this->de($t)."tausend".($n%1000?$this->de($n%1000):"");
    }

    return $n;
}

private function ja($n){
    $nums=['','一','二','三','四','五','六','七','八','九'];
    $units=['','十','百','千'];

    if($n==0)return "零";

    $s='';
    $i=0;

    while($n>0){
        $d=$n%10;
        if($d) $s=$nums[$d].$units[$i].$s;
        $n=intval($n/10);
        $i++;
    }
    return $s;
}
}
