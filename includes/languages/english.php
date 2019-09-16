<?php 

    function lang( $phrase ){

        static $lang = array(

            //* Navbar Links ======================================================================================

            'HOME_ADMIN'     => 'Home',
            'CATEGORIES'     => 'Categoires',
            'ITMES'          => 'Items',
            'MEMBERS'        => 'Members',
            'STATISTICS'     => 'Statistics',
            'LOGS'           => 'logs',
            'VISITE_SHOP'    =>'Visit Shop',
            'EDIT_PROFILE'   => 'Edit Profile',
            'SETTING'        => 'Setting',
            'LOGOUT'         => 'Log Out',
            'COMMENTS'       =>'Comments',

            //* Members ======================================================================================
            
            //* Edit Page
            
            'EDIT_TITLE'     => 'Edit Member',
            'USERNAME'       => 'Username',
            'PASSWORD'       => 'Password',
            'EMAIL'          => 'Email',
            'FULL_NAME'      => 'Full Name',
            'USER_ABATER'    => 'User Photo',
            'UPDATE'         => 'Save',

            //* Update Page

            'UPDATE_TITLE'   => 'Update Member',

            //* Add Page

            'ADD_TITLE'      => 'Add New Member',
            'ADD_MEMBER'     => 'Add Member',
            
            //* Manage Page

            'MANAGE_TITLE'          => 'Manage Members',
            'ADD_MEMBER'            => 'New Member',
            'EDIT_MEMBER'           => 'Edit',
            'Delete_MEMBER'         => 'Delete',
            'ACTIVITE_MEMBER'       => 'Activate',


            //* Dashboard Page ======================================================================================

            'DASHBOARD_TITLE'   => 'Dashboard',
            'MEMERS'            => 'Members',
            'PENDING_MEMBERS'   => 'Pending Memebers',
            'ITMES'             =>'Items',
            'COMMENTS'          =>'Comments',
            'APPROVE_ITEM'      =>'Approve',
            
            //* Categoiry ======================================================================================
            
            //* Mange Categoiry Page

            'MANAGE_CATEGOIRES'     =>'Manage Categoires',
            'ORDERING'              =>'Ordering',
            'VIEW'                  =>'View',
            'ASC'                   =>'Asc',
            'DECS'                  =>'Decs',
            'FULL'                  =>'Full',
            'CLASSIC'               =>'Classic',

            //* Add Categoiry Page
            
            'ADD_GATEGOIRY'     =>'Add New Categoiry',
            'NAME'              =>'Name',
            'Description'       =>'Description',
            'CAT_TYPE'          =>'Parent ? ',
            'ORDERING'          =>'Ordering',
            'VISIABLE'          =>'Visiable',
            'ALLOW_COMMENTING'  =>'Allow Commenting',
            'ALLOW_ADS'         =>'Allow Ads',
            'ADDING_BUTTOM_CATEGOIRY'     =>'Add Categoiry',
            'YES'               =>'Yes',
            'NO'                =>'No',

            //* Edit Cateogiry
            'SAVING_BUTTOM'     =>'Save',
            'EDIT_GATEGOIRY'    =>'Edit Categoiry',
            
            //* Items Page ======================================================================================

            //* Add Page
            'ADD_ITEM'              =>'Add New Item',
            'ADDING_BUTTOM_ITEM'    =>'Add Item',
            'IT_DESCRIPTION'        =>'Description',
            'IT_PRICE'              =>'Price',
            'TAGS'                  => 'Tags',
            'IT_COUNTRY'            =>'Country',
            'IT_STATIS'             =>'Status',
            'IT_MEMBER'             =>'Members',
            'IT_CATEGOIRY'          =>'Categoires',
            
            //* manage Page
            'IT_MANAGE_TITLE'       =>'Manage Items',
            'ADD_ITEMS'             =>'New Item',
            'Approve_MEMBER'        =>'Approve',
            //* Edit page
            'EDIT_ITEM'         =>'Edit Item',
            'SAVE_BUTTOM_ITEM'  =>'Save Item',

            //* Comments ======================================================================================
            
            //* manage page
            'COMMENT_TITLE' =>'Manage Comments',
            'APPROVE_COMMENT' =>'Approve',

            //* Edit Page
            'EDIT_Comment' =>'Edit Comments',
            'COMMENT' =>'Comment',
            '' =>'',
            '' =>'',
            '' =>'',
            '' =>'',
            // Setting Page
            
        );
        
        return $lang[$phrase];

    }
