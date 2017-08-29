<?php
namespace app\api\controller\v1;
use app\api\validate\AddressNew;
use app\api\service\Token as TokenService;
use app\api\model\User as UserModel;
use app\lib\exception\UserException;
use app\lib\exception\SuccessMessage;
use app\lib\enum\ScopeEnum;
use app\lib\exception\ForbiddenException;

// 写入客户地址接口
class Address extends Base{

    //前置操作
    protected $beforeActionList=[
        // 在调用createOrUpdateAddress接口之前先执行checkPrimaryScope方法
        'checkPrimaryScope'=>['only'=>'createOrUpdateAddress']
    ];



    /**
     * 需要一定的权限才能访问此接口
     * [createOrUpdateAddress 创建或更新客户收货地址]
     * @return [type] [description]
     */
    public function createOrUpdateAddress(){
        $validate=new AddressNew();
        $validate->doCheck();
        // (new AddressNew())->doCheck();

        // 1.根据token来获取uid
        // 2.根据uid来查找用户数据，判断用户是否存在，不存在就抛出异常
        // 3.获取用户从客户端提交来的地址信息
        // 4.根据用户地址信息是否存在，从而判断是添加操作还是更新操作

        $uid=TokenService::getCurrentUID();

        $user=UserModel::get($uid);
        if(!$user){
            throw new UserException();
        }

        $dataArray=$validate->getDataByRule(input('post.'));//获取用户提交的地址信息
        /*{"name":"rehack","mobile":"13544448876","province":"四川","city":"成都","country":"锦江","detail":"明宇"}*/

        $userAddress=$user->address;
        if(!$userAddress){
            $user->address()->save($dataArray);//新增
        }else{
            $user->address->save($dataArray);//更新
        }

        // return $user;
        return json(new SuccessMessage(),201);

    }


}
