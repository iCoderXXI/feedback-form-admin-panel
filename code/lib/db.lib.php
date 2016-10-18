<?php /* ЙЦУКЕН */

	require_once (PATH_INI.'/db.ini.php');

	function Strip($s) { return $s; }

	function DBReadPaged($prm=array(), $limitMax = 50) {
		global $APP_CONFIG, $DB;

		if (!$DB) $DB = initDB();

		$page = $prm['page']+0;

		/*// Количество запрашиваемых записей*/
		$limit = $prm['rows']+0;
		if (!$limit || $limit<1) $limit = $APP_CONFIG['OUTPUT']['ROWS_CNT'];
		if (!$limit || $limit<1) $limit = DEF_PG_ROWS;
		if (!$limit || $limit<1) $limit = 1;

		if ($limit>$limitMax) $limit = $limitMax;

		/* Номер элемента массива по которому*/
		// следует производить сортировку
		// Проще говоря поле, по которому
		// следует производить сортировку
		$sidx = $prm['sidx'];

		// Направление сортировки
		$sord = $prm['sord'];

		if (isset($prm['orderBy']) && !empty($prm['orderBy'])) {
			$orderBy = $prm['orderBy'];
		} else {
			$orderBy = $prm['sidx'].' '.$prm['sord'];
		}

		// Если не указано поле сортировки,
		// то производить сортировку по первому полю
		if(!$sidx) $sidx =1;

		$searchOn = Strip($prm['_search']);
		if($searchOn=='true') {
			$allowedFields = $prm['allowedFields']; //array(/*'short', 'country'*/);
			$foreignFields = $prm['foreignFields'];
			$allowedOperations = $prm['allowedOperations']; //array(/*'AND', 'OR'*/);
			$searchData = json_decode(stripslashes($prm['filters']));
			//ограничение на количество условий
			if (count($searchData->rules) > 15) {
				throw new Exception('Cool hacker is here!!! :) x');
			}

			$qWhere = '';
			$firstElem = true;

			//объединяем все полученные условия
			if (count($searchData->rules)>0) {
				$qWhere = ' WHERE ';
				$qWC = 0;
				$qHaving = ' HAVING ';
				$qHC = 0;
				foreach ($searchData->rules as $rule) {
					if (!$firstElem) {
						//объединяем условия (с помощью AND или OR)
						if (in_array($searchData->groupOp, $allowedOperations)) {
							if (isset($foreignFields[$rule->field])) {
								if ($qHC>0) $qHaving .= ' '.$searchData->groupOp.' ';
							} else {
								if ($qWC>0) $qWhere .= ' '.$searchData->groupOp.' ';
							}
						}
						else {
							//если получили не существующее условие - возвращаем описание ошибки
							throw new Exception('Cool hacker is here!!! :)');
						}
					} else {
						$firstElem = false;
					}

					//вставляем условия
					if (in_array($rule->field, $allowedFields)) {

						if (!is_array($rule->data)) {
							$rule->data = addslashes(trim($rule->data));
						}

						$qRule = '';
						switch ($rule->op) {
							case 'eq': $qRule = $rule->field.' = \''.$rule->data.'\''; break;
							case 'ne': $qRule = $rule->field.' <> \''.$rule->data.'\''; break;
							case 'bw': $qRule = ''.$rule->field.' '.
								' LIKE \''.$rule->data.'%'.'\''; break;
							case 'cn': $qRule = ''.$rule->field.''.
								' LIKE \''.'%'.$rule->data.'%'.'\''; break;
							case 'in': $qRule = $rule->field.' IN ('.implode(',',$rule->data).')'; break;
							case 'gt': $qRule = $rule->field.' > '.$rule->data.''; break;
							case 'lt': $qRule = $rule->field.' < '.$rule->data.''; break;
							default: throw new Exception('Cool hacker is here!!! :)');
						}
						if (isset($foreignFields[$rule->field])) {
							$qHC++;
							$qHaving .= $qRule;
						} else {
							$qWC++;
							$qWhere .= $qRule;
						}

					} else {
						//если получили не существующее условие - возвращаем описание ошибки
						throw new Exception('Cool hacker is here!!! :) '); //.$rule->field);
					}
				}
			}
		}
		//if (!isset($qHaving)) $qHaving = FALSE;
		//if (!isset($qWhere)) $qWhere = FALSE;
		if ($qHaving == ' HAVING ') $qHaving='';
		if ($qWhere == ' WHERE ') {
			if(isset($prm['where']) && ($prm['where']!='')) {
			    $qWhere.=$prm['where'];
			} else {
			    $qWhere='';
			}
		} elseif ($qWhere == '') {
			if(isset($prm['where']) && ($prm['where']!='')) {
			    $qWhere.=" WHERE ".$prm['where'];
			}
		} else {
			if(isset($prm['where']) && ($prm['where']!='')) {
			    $qWhere.=" AND ".$prm['where'];
			}
		}
		$sql_count =
			(isset($prm['sql_count_prefix'])?$prm['sql_count_prefix']:'')
			.(isset($prm['sql_count'])?$prm['sql_count']:'')
			.' '.$qWhere
			.' '.(!empty($prm['sql_group'])?"GROUP BY ".$prm['sql_group']."\n":'')
			.' '.$qHaving
			.' '.(isset($prm['sql_count_postfix'])?$prm['sql_count_postfix']:'');

		$count_data = $DB->selectRow($sql_count);
		$count = $count_data[array_keys($count_data)[0]];
		if( $count >0 ) {
			$total_pages = ceil($count/$limit);
		} else {
			$total_pages = 0;
		}

		if (!empty($count_data)) foreach($count_data as $k=>$v) {
			if ($k!=='cnt') $count_data[$k] = number_format($v,2,'.',' ');
		}

		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit; // do not put $limit*($page - 1)
		if ($start<0) $start = 0;
		$SQL =	$prm['sql_read']."\n".
				$qWhere."\n".
				(!empty($prm['sql_group'])?"GROUP BY ".$prm['sql_group']."\n":'').
				$qHaving." \n".
				"ORDER BY $orderBy "."\n".
				"LIMIT $start , $limit"."\n";
		//varExport($prm);
//		print_rd($SQL);
		$responce['page'] = $page;
		$responce['total'] = $total_pages;
		$responce['records'] = $count;
		$responce['rows'] = $DB->select($SQL);
		$responce['count_data'] = $count_data;

		//$responce['query'] = $SQL;
		return $responce;

	}
