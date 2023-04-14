# A3DataBaseWhere

### Usage
```php
where($where,$vars = null);
/* example */
where('id=? and name = ? and age > ?',[1,'user',25]);
where(function($where,$var1,$var2){
	//Where methods
	$where->where($key,$comparer,$value = null);
	/*
	$where->where('name','user');	//$comparer will be '=' if $value is null
	$where->where('age','>',25);
	$where->where(function($where1,$var3,$var4){
	
	},[$var3,$var4]);
	*/
	$where->orWhere($key,$comparer,$value = null);
	/*
	$where->orWhere('name','user');	//$comparer will be '=' if $value is null
	$where->orWhere('age','>',25);
	$where->orWhere(function($where1,$var3,$var4){
	
	},[$var3,$var4]);
	*/
	$where->between($key,$min,$max);
	/* $where->between('age',25,35) */
	$where->orBetween($key,$min,$max);
	/* $where->orBetween('age',25,35) */
	$where->notBetween($key,$min,$max);
	/* $where->notBetween('age',25,35) */
	$where->orNotBetween($key,$min,$max);
	/* $where->orNotBetween('age',25,35) */
	$where->in($key,$array);
	/* $where->in('id',[1,2,3, ...]) */
	$where->orIn($key,$array);
	/* $where->orIn('id',[1,2,3, ...]) */
	$where->notIn($key,$array);
	/* $where->notIn('id',[1,2,3, ...]) */
	$where->orNotIn($key,$array);
	/* $where->orNotIn('id',[1,2,3, ...]) */
},[$var1,$var2]);
```