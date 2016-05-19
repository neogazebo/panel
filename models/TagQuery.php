<?php
namespace common\models;
/**
 * Description of TagQuery
 *
 * @author ilham
 */

use Yii;
use yii\db\ActiveQuery;

class TagQuery extends ActiveQuery{
    //put your code here
    public function getCategoryTagList($id)
    {
        $response = (new yii\db\Query())
            ->select('a.tag_id, a.tag_name')
            ->from('tbl_tag a')
            ->innerJoin('tbl_tag_category z', 'z.tac_tag_id = a.tag_id')
            ->innerJoin('tbl_tag y', 'z.tac_tag_parent_id = y.tag_id')
            ->innerJoin('tbl_company_category x', 'x.com_category_id = y.tag_com_category_id')
            ->where('x.com_category_id = :category', [':category' => $id])
            ->all();
        return  $response;
    }


    public function getTagList()
    {
        $query = (new yii\db\Query)
                ->select('tag_id AS id, tag_name AS text')
                ->from('tbl_tag')
                ->innerJoin('tbl_company_tag', 'cot_tag_id = tag_id')
                ->groupBy('tag_id')
                ->all();
        return $query;
    }
    
    public function getTag()
    {
        $id = isset($_GET['category']) ? $_GET['category'] : 0;
        $query = (new yii\db\Query())
            ->select('tag_id AS id, tag_name AS text')
            ->from('tbl_tag')
            ->leftJoin('tbl_tag_category b', 'b.tac_tag_id = tag_id')
            ->where('b.tac_tag_parent_id = :tag', [':tag' => $id])
            ->all();
        return $query;
    }
    
    public function getChoosenTag()
    {
        $category = isset($_GET['category']) ? $_GET['category'] : 0;
        $com_id = isset($_GET['com_id']) ? $_GET['com_id'] : 0;
        $chosen = null;
        if ($com_id > 0) {
            $chosen = (new yii\db\Query())
                ->select('tag_id AS id, tag_name AS text')
                ->from('tbl_tag')
                ->innerJoin('tbl_company_tag c', 'c.cot_tag_id = tag_id')
                ->leftJoin('tbl_tag_category b', 'b.tac_tag_parent_id = tag_id')
                ->where('c.cot_com_id = :com_id AND b.tac_tag_parent_id = :tag', [
                    ':com_id' => $com_id,
                    ':tag' => $category
                ])
                ->all();
        }
        return $chosen;
    }
}
