<?php 

enum UserRoles :string {
    case Employee = 'employee';
    case Methodologist = 'methodologist';
    case Tester = 'tester';
    case Director = 'director';
}