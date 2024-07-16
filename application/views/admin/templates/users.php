<?php
defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="admin-page-users" class="admin-page">
    <div class="admin-page-header ui inverted blue segment">
        <h1 class="ui inverted header">
            <div class="content">
                Users
                <div class="sub header">
                    Manage administrator users
                </div>
            </div>
        </h1>

        <div class="ui green labeled icon button" ng-click="create()">
            <i class="add icon"></i>
            Create
        </div>
    </div>
    <div class="admin-page-content">
        <div class="ui raised segment">
            <div class="ui header">
                All Users
            </div>
            <div class="ui selection very relaxed middle aligned divided large list">
                <div class="item" ng-repeat="user in users">
                    <div class="right floated content">
                        <div class="ui basic icon buttons">
                            <div class="ui button" ng-click="edit($index)">
                                <i class="pencil alternative icon"></i>
                            </div>
                            <div class="ui button" ng-click="remove($index)">
                                <i class="trash icon"></i>
                            </div>
                        </div>
                    </div>
                    <div class="content">
                        <h3 class="ui header">
                            <i ng-class="user.active ? 'check icon' : 'minus circle icon'"></i>
                            <div class="content">
                                {{ user.first_name }} {{ user.last_name }}
                                <div class="sub header">{{ user.email }}</div>
                            </div>
                        </h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
