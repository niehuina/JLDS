<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
}

class User_GradeLogModel extends User_GradeLog
{
    /**
     * 读取Log列表
     *
     * @param  array $cond_row 查询条件
     * @param  array $order_row 排序信息
     * @return array $rows 返回的查询内容
     * @access public
     */
    public function getGradeLogList($cond_row = array(), $order_row = array())
    {
        return $this->getByWhere($cond_row, $order_row);
    }

    /**
     * 查询下级用户中1年内升级成为会员的用户数量
     * @param array $cond_row
     * @param array $order_row
     * @return integer
     */
    public function getUserCount($user_id, $user_grade, $start_date, $end_date)
    {
        //1.查出下级用户
        $query_row['user_parent_id'] = $user_id;
        $query_row['user_grade'] = ['>=', $user_grade];
        $User_InfoModel = new User_InfoModel();
        $ids_list = $User_InfoModel->getKeyByWhere($query_row);

        //2.查询下级用户中1年内升级成为会员的用户
        $cond_row['user_id'] = ['in', $ids_list];
        $cond_row['user_grade_to'] = $user_grade;
        $cond_row['log_date_time'] = ['>=', $start_date];
        $cond_row['log_date_time'] = ['<=', $end_date];
        $list = $this->getByWhere($cond_row);

        $user_ids = array();
        if($list){
            $user_ids = array_column($list, 'user_id');
            $user_ids = array_unique($user_ids);//去重
        }

        return count($user_ids);
    }

    /**
     * 根据条件查询用户id
     * @param $cond_row
     * @param $order_row
     * @return mixed
     */
    public function getUserIdBySql($cond_row, $order_row){
        $sql = 'SELECT SQL_CALC_FOUND_ROWS user_id FROM ' . $this->_tableName;

        if ($cond_row)
        {
            foreach ($cond_row as $k => $v)
            {
                $k_row = explode(':', $k);

                if (count($k_row) > 1)
                {
                    $this->sql->setWhere($k_row[0], $v, $k_row[1]);
                }
                else
                {
                    $this->sql->setWhere($k, $v);
                }

            }
        }

        if ($order_row)
        {
            foreach ($order_row as $k => $v)
            {
                $this->sql->setOrder($k, $v);
            }
        }

        $this->sql->setGroup('user_id');

        $where = $this->sql->getWhere();
        $group = $this->sql->getGroup();
        $order = $this->sql->getOrder();
        $sql   = $sql . $where . $group . $order;

        $rs = $this->sql->getAll($sql);

        return $rs;
    }
}