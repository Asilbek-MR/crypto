<?php
defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="admin-page-user" class="admin-page">
    <div class="admin-page-content">
        <div class="ui borderless mini labeled icon menu">
            <div class="active link item" ui-sref="users">
                <i class="reply icon"></i>
                Users
            </div>
            <div class="link item" ng-click="save()">
                <i class="save icon"></i>
                Save
            </div>
            <div class="right menu">
                <div class="link item" ng-click="remove()" ng-if="user.id">
                    <i class="trash icon"></i>
                    Delete
                </div>
            </div>
        </div>

        <div class="ui raised segment">
            <div class="field">
                <div class="ui toggle checkbox">
                    <input type="checkbox" ng-model="user.active" ng-true-value="1" ng-false-value="0">
                    <label>Active</label>
                </div>
            </div>
            <div class="three fields">
                <div class="field">
                    <label>First Name</label>
                    <input type="text" placeholder="Jon" ng-model="user.first_name">
                </div>
                <div class="field">
                    <label>Last Name</label>
                    <input type="text" placeholder="Snow" ng-model="user.last_name">
                </div>
                <div class="field">
                    <label>Email</label>
                    <input type="email" placeholder="mail@example.com" ng-model="user.email">
                </div>
            </div>
            <div class="ui orange message" ng-if="user.id">
                <div class="content">
                    Enter password only if you want to change it.
                </div>
            </div>
            <div class="two fields">
                <div class="field">
                    <label>Password (8-20 length)</label>
                    <input type="password" ng-model="passwords[0]">
                </div>
                <div class="field">
                    <label>Confirm Password</label>
                    <input type="password" ng-model="passwords[1]">
                </div>
            </div>
        </div>

    </div>
</div>