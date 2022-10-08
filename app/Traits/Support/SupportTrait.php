<?php

namespace App\Traits\Support;

use App\Models\User;

trait SupportTrait{

    function createMainSupport($request, $supportModelInstance, $uniqueId, $userObject){
        $mainSupoort = $supportModelInstance;
        $mainSupoort->unique_id = $uniqueId;
        $mainSupoort->user_id = $userObject->unique_id;
        $mainSupoort->category_unique_id = $request->category_unique_id;
        $mainSupoort->topic = $request->topic;
        $mainSupoort->save();
        return $mainSupoort;
    }

    function createMessageSupport($messageUniqueId, $mainSupportUniqueId, $request, $supportMessageInstance){
        $messageTable = $supportMessageInstance;
        $messageTable->unique_id = $messageUniqueId;
        $messageTable->support_unique_id = $mainSupportUniqueId;
        $messageTable->message = $request->message;
        $messageTable->sender_id = $request->sender_id;
        $messageTable->reciever_id = $request->reciever_id;
        $messageTable->save();
        return $messageTable;
    }

    function updateSupportToRead($supportObject, $supportModelInsatance, $userObject){
        if($supportObject->read_status === $supportModelInsatance->supportMessageUnreadStatus && $userObject->unique_id === $supportObject->admin_id){
            $supportObject->read_status = $supportModelInsatance->supportMessageReadStatus;
            $supportObject->save();
        };
    }

    function updateSupportMessageToRead($supportObject, $supportModelInsatance, $userObject){
        $allSupportUnreadMessages = $supportObject->support_message_array()->where('read_status', $supportModelInsatance->supportMessageUnreadStatus)->where('reciever_id', $userObject->unique_id)->get();
        if(count($allSupportUnreadMessages) > 0){
            foreach($allSupportUnreadMessages as $k => $eachUnreadMessageObject){
                $eachUnreadMessageObject->read_status = $supportModelInsatance->supportMessageReadStatus;
                $eachUnreadMessageObject->save();
            }
        };
    }

    function returnUserModel(){
        return new User();
    }

    function getReadStatusCountForAllMessage($userObject, $supportModelInsatance, $supportMessageModelInsatance){

        $userModelInstance = $this->returnUserModel();
        $count = 0;

        if($userObject->type_of_user !== $userModelInstance->normalUserType){
            $supportQuery = $supportModelInsatance::where('admin_id', null)->where('read_status', $supportModelInsatance->supportMessageUnreadStatus);
            $count = $supportQuery->count();
        }

        $allSupportMessagesCount = $supportMessageModelInsatance::where('reciever_id', $userObject->unique_id)->where('read_status', $supportModelInsatance->supportMessageUnreadStatus)->count();

        $allUnreadSupportMessageCount = $count + $allSupportMessagesCount;
        return ['all_unread_support_message_count'=>$allUnreadSupportMessageCount];

    }

}
?>