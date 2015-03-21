<?php
/*! php-int 1.0.0 | Copyright (c) 2015 Lucas Krause | New BSD License | http://php-int.futape.de */

namespace futape\int;

use InvalidArgumentException, Exception;

class Int {



    #static-functions#
    
    #Int::castArray()#
    /**
     * array castArray( mixed $value )
     *
     * Converts a value of any type into an array.
     * If the passed value is already an array, no conversion is done. Otherwise, a new array with the value as its only items is created.
     * This is similar to using `(array)$value`, except for the behavior when [casting an object as an array](http://php.net/manual/en/language.types.array.php#language.types.array.casting).
     *
     * +   `$value`: The value to be turned into an array.
     *
     * Returns the created or passed array.
     */
    private static function castArray($mix_a){
        return is_array($mix_a) ? $mix_a : array($mix_a);
    }
    
    #Int::arrayConcat()#
    /**
     * array arrayConcat( array $values )
     *
     * Creates an array containing the values of the array passed to this function.
     * Array-values aren't kept as they are, instead their values are used.
     * Any other values are used directly to be contained in the created array.
     *
     * +   `$values`: The values to be concatenated.
     *
     * Returns the created array containing all the passed values.
     */
    private static function arrayConcat($arr_arrs) {
        $arr_a=array();

        foreach($arr_arrs as $val){
            array_splice($arr_a, count($arr_a), 0, self::castArray($val));
        }

        return $arr_a;
    }
    
    
    
    #static-int-functions#

    #Int::min()#
    /**
     * Int|null min( [ Int|string|(Int|string)[] $int1 [, Int|string|(Int|string)[] $int2 [, ... ]]] )
     *
     * Searches for the lowest value in the passed ones.
     *
     * +   `$int1, $int2, ...`: The values in which to search for the lowest one. Arrays passed to this function are merged together with the other parameters' values. String values are turned into a new `Int` object.
     *
     * Returns the lowest value as an `Int` object or `null` if no values or empty arrays only have been passed to this function.
     */
    public static function min(){
        $arr_args=func_get_args();
        $arr_args=self::arrayConcat($arr_args);
        $str_self=__CLASS__;

        array_walk($arr_args, function(&$val) use ($str_self){
            if(!($val instanceof $str_self)){
                $val=new $str_self($val);
            }
        });

        $fint_min=null;

        foreach($arr_args as $i=>$val){
            if(is_null($fint_min) || $val->lt($fint_min)){
                $fint_min=$val;
            }
        }

        return $fint_min;
    }
    
    #Int::max()#
    /**
     * Int|null max( [ Int|string|(Int|string)[] $int1 [, Int|string|(Int|string)[] $int2 [, ... ]]] )
     *
     * Searches for the highest value in the passed ones.
     *
     * +   `$int1, $int2, ...`: The values in which to search for the highest one. Arrays passed to this function are merged together with the other parameters' values. String values are turned into a new `Int` object.
     *
     * Returns the highest value as an `Int` object or `null` if no values or empty arrays only have been passed to this function.
     */
    public static function max(){
        $arr_args=func_get_args();
        $arr_args=self::arrayConcat($arr_args);
        $str_self=__CLASS__;

        array_walk($arr_args, function(&$val) use ($str_self){
            if(!($val instanceof $str_self)){
                $val=new $str_self($val);
            }
        });

        $fint_max=null;

        foreach($arr_args as $i=>$val){
            if(is_null($fint_max) || $val->gt($fint_max)){
                $fint_max=$val;
            }
        }

        return $fint_max;
    }
    
    #Int::rand()#
    /**
     * Int rand( Int|string $max [, Int|string $min = "0" ] )
     *
     * Returns a random number in the range between `$min` and `$max`.
     *
     * +   `$max`: The highest number (inclusive) the random number can be. If not already an `Int` object, it's turned into one.
     *             If lower than `$min` or if negative, an [`Exception`](http://php.net/manual/en/class.exception.php) is thrown.
     * +   `$min`: The lowest number (inclusive) the random number can be. If not already an `Int` object, it's turned into one.
     *             If negative, an [`Exception`](http://php.net/manual/en/class.exception.php) is thrown.
     *
     * Returns the generated random integer as an `Int` object.
     */
    public static function rand($fint_max, $fint_min="0"){
        if(!($fint_max instanceof self)){
            $fint_max=new self($fint_max);
        }
        if(!($fint_min instanceof self)){
            $fint_min=new self($fint_min);
        }
        
        if($fint_max->isNeg()){
            throw new Exception("\$max must not be a negative number, was '".$fint_max."'.");
        }
        if($fint_min->isNeg()){
            throw new Exception("\$min must not be a negative number, was '".$fint_min."'.");
        }
        if($fint_min->gt($fint_max)){
            throw new Exception("\$min (was '".$fint_min."') must not be greater than \$max (was '".$fint_max."').");
        }

        $fint_rand=clone $fint_max;
        $int_rand=mt_rand(0, mt_getrandmax()-1);
        
        $fint_rand->add("1");
        $fint_rand->sub($fint_min);
        $fint_rand->mult((string)$int_rand);
        $fint_rand->div((string)mt_getrandmax());
        $fint_rand->add($fint_min);

        return $fint_rand;
    }
    
    
    
    #properties#
    
    #Int::$num#
    /**
     * string $num
     *
     * The absolute value of the number represented by the `Int` object.
     * A value of 0 is represented by an empty string. Leading zeroes should never appear.
     */
    private $num;
    
    #Int::$isNeg#
    /**
     * bool $isNeg
     *
     * Whether the number represented by the `Int` object is a negative one.
     */
    private $isNeg;
    
    
    
    #init-functions#
    
    #Int::__construct()#
    /**
     * void __contruct( string $number )
     *
     * The `Int` class's constructor, called when a new instance of this class is created.
     * Validates the passed number string and initiates the object's properties.
     * The passed string must begin with an optional `+` or `-`, followed by at least one digit and an optional *decimal part* consisting of a dot (the decimal point) and one or more digits. If a decimal part is specified, the preceding digits may be skipped, assuming a value of 0 instead. No other characters may be included in the string.
     * Since this is an *interger* library and not a float library, the decimal part is ignored entirely.
     * A leading `-` character indicated a negative number. Otherwise, a positive one is assumed.
     * If the passed string doesn't pass the validation, an [`InvalidArgumentException`](http://php.net/manual/en/class.invalidargumentexception.php) is thrown.
     *
     * +   `$number`: The number string describing the number that should be represented by the object.
     */
    public function __construct($str_num){
        if(preg_match('/^([+-]?)(\d*(?=\.\d+$)|\d+$)/', $str_num, $arr_a)!=1){
            throw new InvalidArgumentException("Number string must consist of an optional leading '-' or '+' and numeric characters and an optional decimal point only, was: '".$str_num."'.");
        }
        
        $this->setNum($arr_a[2]);
        $this->setNeg($arr_a[1]=="-");
    }
    
    
    
    #getter-functions#
    
    #Int::__toString()#
    /**
     * string __toString()
     *
     * This function is called when an `Int` object is casted as a string, for example when it's concatenated with another string. Instead of the object itself, the returned value is used.
     *
     * Returns the object's [`get()`](#) method's return value.
     */
    public function __toString(){
        return $this->get();
    }
    
    #Int::get()#
    /**
     * string get()
     *
     * Returns the value of the object as a string.
     */
    public function get(){
        return ($this->isNeg() ? "-" : "").($this->eq("0") ? "0" : $this->num);
    }
    
    #Int::getAbs()#
    /**
     * string getAbs()
     *
     * Returns the absolute value of the object as a string.
     */
    public function getAbs(){
        $fint_a=clone $this;

        $fint_a->abs();

        return $fint_a->get();
    }
    
    #Int::isNeg()#
    /**
     * bool isNeg()
     *
     * Checks whether the current value is negative (i.e. it's lower than 0).
     *
     * If the current object's value is negative, `true` is returned, otherwise `false` is returned.
     */
    public function isNeg(){
        /**
         * use $isNeg instead of lt("0"), since that function uses cmp() which in turn calls isNeg() (this function), causing a loop
         */
        return $this->isNeg;
    }
    
    #Int::eq()#
    /**
     * bool eq( Int|string $compare )
     *
     * Checks whether the current value equals the passed one.
     * This is equal to [`$int->cmp($compare)==0`](#).
     *
     * +   `$compare`: The object to compare the current one against. If not already an `Int` object, it's turned into one.
     *
     * If the current object's value is equal to the passed one, `true` is returned, otherwise `false` is returned.
     */
    public function eq($fint_a){
        return $this->cmp($fint_a)==0;
    }
    
    #Int::gt()#
    /**
     * bool gt( Int|string $compare )
     *
     * Checks whether the current value is greater than the passed one.
     * This is equal to [`$int->cmp($compare)>0`](#).
     *
     * +   `$compare`: The object to compare the current one against. If not already an `Int` object, it's turned into one.
     *
     * If the current object's value is greater than the passed one, `true` is returned, otherwise `false` is returned.
     */
    public function gt($fint_a){
        return $this->cmp($fint_a)>0;
    }
    
    #Int::gte()#
    /**
     * bool gte( Int|string $compare )
     *
     * Checks whether the current value is greater than or equal to the passed one.
     *
     * +   `$compare`: The object to compare the current one against. If not already an `Int` object, it's turned into one.
     *
     * If the current object's value is greater than or equal to the passed one, `true` is returned, otherwise `false` is returned.
     */
    public function gte($fint_a){
        return ($this->gt($fint_a) || $this->eq($fint_a));
    }
    
    #Int::lt()#
    /**
     * bool lt( Int|string $compare )
     *
     * Checks whether the current value is lower than the passed one.
     * This is equal to [`$int->cmp($compare)<0`](#).
     *
     * +   `$compare`: The object to compare the current one against. If not already an `Int` object, it's turned into one.
     *
     * If the current object's value is lower than the passed one, `true` is returned, otherwise `false` is returned.
     */
    public function lt($fint_a){
        return $this->cmp($fint_a)<0;
    }
    
    #Int::lte()#
    /**
     * bool lte( Int|string $compare )
     *
     * Checks whether the current value is lower than or equal to the passed one.
     *
     * +   `$compare`: The object to compare the current one against. If not already an `Int` object, it's turned into one.
     *
     * If the current object's value is lower than or equal to the passed one, `true` is returned, otherwise `false` is returned.
     */
    public function lte($fint_a){
        return ($this->lt($fint_a) || $this->eq($fint_a));
    }
    
    
    
    #setter-functions#

    #Int::setNeg()#
    /**
     * void setNeg( bool $is_negative )
     *
     * Sets the value of the object's `$isNeg` property.
     * The property is set to the passed value unless the object's `$num` property's current value is `""` and the `$isNeg` property should be set to `true`. In that case, it's set to `false` instead.
     *
     * +   `$is_negative`: The value, the `$isNeg` property should be set to.
     */
    private function setNeg($q_neg){
        $this->isNeg=($q_neg && $this->num!="");
    }
    
    #Int::updateNeg()#
    /**
     * void updateNeg()
     *
     * Updates the object's `$isNeg` property's value.
     * The value is kept unless it was `true` and the current value of the `$num` property is `""`. In that case, it's set to `false`.
     */
    private function updateNeg(){
        $this->setNeg($this->isNeg);
    }
    
    #Int::toggleNeg()#
    /**
     * void toggleNeg()
     *
     * Toggle the value of the object's `$isNeg` property.
     * A value of `true` becomes `false`, and a value of `false` is changed to `true`.
     */
    private function toggleNeg(){
        $this->setNeg(!$this->isNeg);
    }
    
    #Int::setNum()#
    /**
     * void setNum( string $integer )
     *
     * Sets the value of the object's `$num` property.
     * The property is set to the passed value with leading zeroes stripped away.
     * Also the `$isNeg` property is updated according to the `$num` property's new value. See [`updateNeg()`](#) for more information.
     *
     * +   `$integer`: The integer string, the `$num` property is set to.
     */
    private function setNum($str_int){
        $str_int=ltrim($str_int, "0");
        
        $this->num=$str_int;
        
        $this->updateNeg(); //set $isNeg to false if $num is "" (0), otherwise leave it as it is
    }

    
    
    #calc-constants#
    
    #Int::CALC_ADD#
    /**
     * string CALC_ADD
     *
     * The arithmetic operator for additions.
     */
    const CALC_ADD="+";
    
    #Int::CALC_SUB#
    /**
     * string CALC_SUB
     *
     * The arithmetic operator for subtractions.
     */
    const CALC_SUB="-";
    
    #Int::CALC_MULT#
    /**
     * string CALC_MULT
     *
     * The arithmetic operator for multiplications.
     */
    const CALC_MULT="*";
    
    #Int::CALC_POW#
    /**
     * string CALC_POW
     *
     * The arithmetic operator for exponentiations.
     */
    const CALC_POW="**";
    
    #Int::CALC_DIV#
    /**
     * string CALC_DIV
     *
     * The arithmetic operator for divisions.
     */
    const CALC_DIV="/";
    
    #Int::CALC_MOD#
    /**
     * string CALC_MOD
     *
     * The arithmetic operator for moduli.
     */
    const CALC_MOD="%";
    
    
    
    #calc-properties#
    
    #Int::$calc_ops#
    /**
     * string[] $calc_ops
     *
     * A mapping between arithmetic operators ([`Int::CALC_*` constants](#)) and the corresponding methods.
     */
    private static $calc_ops=array(
        self::CALC_ADD=>"add",
        self::CALC_SUB=>"sub",
        self::CALC_MULT=>"mult",
        self::CALC_POW=>"pow",
        self::CALC_DIV=>"div",
        self::CALC_MOD=>"mod"
    );
    
    
    
    #functions#

    #Int::add()#
    /**
     * void add( Int|string $summand )
     *
     * Adds the passed object's value to the current one.
     *
     * +   `$summand`: The number to add to the current value. If not already an `Int` object, it's turned into one.
     */
    public function add($fint_add){
        if($fint_add instanceof self){
            $fint_add=clone $fint_add;
        }else{
            $fint_add=new self($fint_add);
        }
        
        if($fint_add->eq("0")){
            return;
        }
        
        $q_neg=$fint_add->isNeg()!=$this->isNeg(); //subtract absolute values instead of adding them

        if($q_neg){
            $fint_a=clone $fint_add;
            $fint_b=clone $this;
            
            $fint_a->abs();
            $fint_b->abs();
            
            if($fint_b->lt($fint_a)){
                $fint_a->sub($fint_b);
                
                $this->num=$fint_a->num;
                $this->toggleNeg();
                
                return;
            }
        }
        
        $str_add=$fint_add->num;
        
        $str_num=$this->num;
        $str_num=str_pad($str_num, strlen($str_add), "0", STR_PAD_LEFT);
        
        $str_add_b=$str_add;
        $str_add=rtrim($str_add, "0");
        $str_zeroesApp=str_repeat("0", strlen($str_add_b)-strlen($str_add));
        
        $str_numApp=$str_zeroesApp!="" ? substr($str_num, -strlen($str_zeroesApp)) : "";
        $str_num=substr($str_num, 0, strlen($str_num)-strlen($str_numApp));
        
        $int_num=(int)substr($str_num, -1);
        $int_add=(int)substr($str_add, -1);
        
        if($q_neg){
            $int_sum=$int_num-$int_add; //(0-9=) -9 <= $int_sum <= (9-1=) 8
            $int_one=(10+$int_sum)%10;
            $int_ten=(int)($int_sum<0); //1|0
        }else{
            $int_sum=$int_num+$int_add; //(0+1=) 1 <= $int_sum <= (9+9=) 18
            $int_one=$int_sum%10;
            $int_ten=(int)($int_sum>=10); //1|0
        }
        
        $str_num=substr($str_num, 0, -1).$int_one.$str_numApp;
        
        $this->setNum($str_num);
        $str_num=$this->num; //leading zeroes have been removed by setNum()
        
        $str_add=substr($str_add, 0, -1)."0".$str_zeroesApp;
        $str_add_b=$int_ten."0".$str_zeroesApp;
        $fint_add_b=clone $fint_add;
        
        $fint_add_b->setNum($str_add_b);
        $fint_add->setNum($str_add);
        $fint_add->add($fint_add_b);
        $str_add=$fint_add->num;
        
        $this->add($fint_add);
    }
    
    #Int::sub()#
    /**
     * void sub( Int|string $subtrahend )
     *
     * Subtracts the passed object's value from the current one.
     *
     * +   `$subtrahend`: The number to subtract from the current value. If not already an `Int` object, it's turned into one.
     */
    public function sub($fint_sub){
        if($fint_sub instanceof self){
            $fint_sub=clone $fint_sub;
        }else{
            $fint_sub=new self($fint_sub);
        }
        
        $fint_sub->toggleNeg();
        
        $this->add($fint_sub);
    }
    
    #Int::mult()#
    /**
     * void mult( Int|string $multiplier )
     *
     * Multiplies the current value with the passed one.
     *
     * +   `$multiplier`: The number to multiply the current value with. If not already an `Int` object, it's turned into one.
     */
    public function mult($fint_mult){
        if($fint_mult instanceof self){
            /** /$fint_mult=clone $fint_mult;/**/ //actually not necessary since $fint_mult is never modified
        }else{
            $fint_mult=new self($fint_mult);
        }
        
        if($fint_mult->eq("0")){
            $this->setNum("0"); //removes also possible sign (i.e. "-")

            return;
        }
        
        $q_neg=$fint_mult->isNeg()!=$this->isNeg();
        
        $str_mult=$fint_mult->num; //multiplier
        $str_num=$this->num; //multiplicand
        
        $arr_prod=array();
        
        for($i=0; $i<strlen($str_mult); $i++){
            $int_mult=(int)substr($str_mult, $i, 1);
            $str_zeroesApp=str_repeat("0", strlen($str_mult)-$i-1);
            
            $str_prod="";
            $int_ten=0;
            
            for($u=strlen($str_num)-1; $u>=0; $u--){
                $int_num=(int)substr($str_num, $u, 1);
                
                $int_prod=$int_num*$int_mult; //0 <= $int_prod <= 81
                $int_prod+=$int_ten; //0 <= $int_prod <= 89
                /** /$int_ten=0;/**/ //unnecessary since $int_ten is set below
                $int_one=$int_prod%10;
                $int_ten=floor($int_prod/10);
                
                $str_prod=$int_one.$str_prod;
            }
            $str_prod=$int_ten.$str_prod;
            
            $str_prod.=$str_zeroesApp;
            
            array_push($arr_prod, new self($str_prod));
        }
        
        $this->setNum("0"); //also removes possible sign (i.e. "-")
        
        foreach($arr_prod as $val){
            $this->add($val);
        }
        
        $this->setNeg($q_neg);
    }
    
    #Int::pow()#
    /**
     * void pow( Int|string $exponent )
     *
     * Executes a exponentiation operation on the current value using the passed one as exponent.
     *
     * +   `$exponent`: A number specifying the power to which to raise the current value to. If not already an `Int` object, it's turned into one.
     */
    public function pow($fint_exp){
        if($fint_exp instanceof self){
            $fint_exp=clone $fint_exp;
        }else{
            $fint_exp=new self($fint_exp);
        }
        
        if($fint_exp->eq("0")){
            $this->setNum("1");
            $this->abs();
        }else if($fint_exp->isNeg()){
            $fint_a=clone $this;
            
            $fint_exp->abs();
            $fint_a->pow($fint_exp);
            
            $this->setNum("1");
            $this->abs();
            $this->div($fint_a);
        }else{ //$fint_exp > 0
            $fint_a=clone $this;
            
            for(; $fint_exp->gt("1"); $fint_exp->sub("1")){
                $this->mult($fint_a);
            }
        }
    }
    
    #Int::sq()#
    /**
     * void sq()
     *
     * Short for [`$int->pow("2")`](#).
     * Raises the current value to the power of 2.
     */
    public function sq(){
        $this->pow("2");
    }

    #Int::div()#
    /**
     * void div( Int|string $divisor )
     *
     * Divides the current value by the passed one.
     *
     * +   `$divisor`: The number by which to divide the current value. If not already an `Int` object, it's turned into one.
     *                 If 0, an [`Exception`](http://php.net/manual/en/class.exception.php) is thrown.
     */
    public function div($fint_div){
        if($fint_div instanceof self){
            /** /$fint_div=clone $fint_div;/**/ //actually not necessary since $fint_mult is never modified
        }else{
            $fint_div=new self($fint_div);
        }
        
        if($fint_div->eq("0")){
            throw new Exception("Dividing by zero is not possible.");
        }
        
        $q_neg=$fint_div->isNeg()!=$this->isNeg();
        
        $fint_a=clone $fint_div;
        $fint_b=clone $this;
        
        $fint_a->abs();
        $fint_b->abs();
        
        if($fint_b->lt($fint_a)){
            $this->setNum("0"); //also removes possible sign (i.e. "-")
            
            return;
        }
        
        $fint_a=clone $fint_div;
        //$fint_b=clone $fint_div;
        
        /** /
        $fint_b->mod("10"); //can't use mod("10") since that in turn calls div("10"), causing a loop
        /*/
        $fint_a->setNum(rtrim($fint_a->num, "0"));
        /**/
        
        $fint_a->abs();
        
        if($fint_a->eq("1") /*|| $fint_b->eq("0")*/){
            $this->setNum(substr($this->num, 0, strlen($this->num)-(strlen($fint_div->num)-1)));
            $this->setNeg($q_neg);
            
            return;
        }
        
        $str_div=$fint_div->num; //divisor
        $arr_div=array(); //divisor split up into decimal powers ("Zehnerpotenzen")
        
        for($i=0; $i<strlen($str_div); $i++){
            array_push($arr_div, str_pad(substr($str_div, $i, 1), strlen($str_div)-$i, "0"));
        }
        
        $str_num=$this->num; //dividend
        $fint_num=new self("0"); //current dividend
        $str_result=""; //quotient
        
        while($str_num!=""){
            $str_digit=substr($str_num, 0, 1);
            $str_num=substr($str_num, 1);
            
            $fint_num->setNum($fint_num->num.$str_digit);
            
            if($fint_num->lt($arr_div[0])){
                $str_result.="0";
                
                continue;
            }
            
            $fint_a=clone $fint_num;
            $str_a=substr($arr_div[0], 0, 1);
            
            if($fint_a->lt($str_a."0")){ //i.e. `(int)$str_a * 10`
                $fint_a->setNum((string)floor((int)$fint_a->num/(int)$str_a)); //1 <= $fint_a <= 89
            }else{
                $fint_a->div($str_a);
            }
            
            $fint_a->div(str_pad("1", strlen($arr_div[0]), "0"));
            $fint_a=self::min($fint_a, "9"); //a single digit only
            
            while($fint_a->gt("0")){
                $fint_b=clone $fint_num;
                
                foreach($arr_div as $val){
                    $fint_c=clone $fint_a;
                    
                    $fint_c->mult($val);
                    
                    $fint_b->sub($fint_c);
                    
                    /**/
                    if($fint_b->isNeg()){
                        break;
                    }
                    /**/
                }
                
                if($fint_b->isNeg()){
                    $fint_a->sub("1");
                }else{
                    $str_result.=$fint_a->num;
                    
                    $fint_num=$fint_b;
                    
                    break;
                }
            }
        }
        
        $this->setNum($str_result);
        $this->setNeg($q_neg);
    }
    
    #Int::mod()#
    /**
     * void mod( Int|string $divisor )
     *
     * Executes a modulus operation on the current value using the passed one as divisor.
     *
     * +   `$divisor`: The number by which to divide the current value before getting the remainder. If not already an `Int` object, it's turned into one.
     *                 See [`div()`](#) for more information.
     */
    public function mod($fint_div){
        if(!($fint_div instanceof self)){
            $fint_div=new self($fint_div);
        }
        
        /* already done by div() below * /
        if($fint_div->eq("0")){
            throw new Exception("Dividing by zero is not possible.");
        }
        /**/
        
        $fint_a=clone $this; //sub
        
        $fint_a->div($fint_div);
        $fint_a->mult($fint_div);
        
        $this->sub($fint_a);
    }
    
    #Int::calc()#
    /**
     * void calc( string $operator, Int|string $number )
     *
     * Calls the method that corresponds to the specified arithmetic operator.
     * The following operators are supported.
     *
     * +   `+` ([`Int::CALC_ADD`](#)): Addition 
     * +   `-` ([`Int::CALC_SUB`](#)): Subtraction 
     * +   `*` ([`Int::CALC_MULT`](#)): Multiplication 
     * +   `**` ([`Int::CALC_POW`](#)): Exponentiation 
     * +   `/` ([`Int::CALC_DIV`](#)): Division 
     * +   `%` ([`Int::CALC_MOD`](#)): Modulus 
     *
     * For any other operator an [`Exception`](http://php.net/manual/en/class.exception.php) is thrown.
     *
     * +   `$operator`: The operator specifying which method to call. Use one of the operators shown above or use one of the [`Int::CALC_*` constants](#).
     * +   `$number`: The number to calculate with. The value is passed to the corresponding function which in turn converts is into an `Int` object if it isn't already one.
     */
    public function calc($str_op, $fint_calc){
        if(!array_key_exists($str_op, self::$calc_ops)){
            throw new Exception("Unknown operator: '".$str_op."'.");
        }
        
        $str_fn=self::$calc_ops[$str_op];
        
        call_user_func(array($this, $str_fn), $fint_calc);
    }
    
    #Int::abs()#
    /**
     * void abs()
     *
     * Makes the object's value an absolute one by removing its sign.
     */
    public function abs(){
        $this->setNeg(false);
    }
    
    #Int::cmp()#
    /**
     * int cmp( Int|string $compare )
     *
     * Compares the current value to the passed one.
     *
     * +   `$compare`: The object to compare the current one against. If not already an `Int` object, it's turned into one.
     *
     * If the values are identical, `0` is returned, if the current one is greater than the passed one, `1` is returned, and if the it's lower than the passed one, `-1` is returned.
     */
    public function cmp($fint_cmp){
        if(!($fint_cmp instanceof self)){
            $fint_cmp=new self($fint_cmp);
        }
        
        $int_diff=0;
        
        if($this->isNeg()!=$fint_cmp->isNeg()){
            $int_diff=1;
        }else{
            $str_num=$this->num;
            $str_cmp=$fint_cmp->num;
            
            if(strlen($str_num)!=strlen($str_cmp)){
                $int_diff=strlen($str_num)>strlen($str_cmp) ? 1 : -1;
            }else{
                for($i=0; $i<strlen($str_num) /*&& $i<strlen($str_cmp)*/; $i++){
                    $int_a=(int)substr($str_num, $i, 1);
                    $int_b=(int)substr($str_cmp, $i, 1);
                    
                    if($int_a!=$int_b){
                        $int_diff=$int_a>$int_b ? 1 : -1;
                        
                        break;
                    }
                }
            }
        }
        
        if($this->isNeg()){
            $int_diff*=-1;
        }
        
        return $int_diff;
    }
    
    
    
}
