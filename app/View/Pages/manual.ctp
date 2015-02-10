<?php
/**
 * Copyright 2014 Hendrik Schmeer on behalf of DARIAH-EU, VCC2 and DARIAH-DE,
 * Credits to Erasmus University Rotterdam, University of Cologne, PIREH / University Paris 1
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

$this->append('menu',
  '
  <li class="filter">Manual and FAQ</li>
  <br>
  <br>
  <li>Register</a></li>
  <li>Log In</li>
  <li>Dashboard</li>
  <li>Add new course</li>
  <li>Special user roles</li>
  <li><a href="/contacts/send">Contact Us</a></li>
  ');


echo $this->element('manual/register');
echo $this->element('manual/login');
echo $this->element('manual/dashboard');
echo $this->element('manual/addnewcourses');

/*
  <li><img src="/img/Hintergrund.png" width="251" height="6000" align="right" vspace="10" hspace="20" alt="Text?"></li>


'<li class="filter">Manual and FAQ</li>

<li><a href="#">Register</a>
  <ul>
  <li><a href="#erstens">Find the form</a></li>
  <li><a href="?12">Submit the form</a></li>
  <li><a href="?13">Verify your email address</a></li>
  <li><a href="?13">Wait for approval</a></li>
  <li><a href="?13">FAQ</a></li>
  </ul>
  </li>
  <li><a href="#">Log In</a>
  <ul>
  <li><a href="?21">Find the Log In</a></li>
  <li><a href="?22">Get new password</a></li>
  <li><a href="?23">FAQ</a></li>
  </ul>
  </li>

  <li><a href="#">Dashboard</a>
  <ul>
  <li><a href="?32">Manage your Profile</a></li>
  <li><a href="?31">See your courses</a></li>
  <li><a href="?32">Manage your courses</a></li>
  <li><a href="?13">FAQ</a></li>
  </ul>
  </li>

  <li><a href="#">Add new course</a>
  <ul>
  <li><a href="?31">Find the form</a></li>
  <li><a href="?31">Fill out the form</a></li>
  <li><a href="?32">Submit the form</a></li>
  <li><a href="?13">FAQ</a></li>
  </ul>
  </li>

  <li>
  <a href="#">Special user roles</a>
  <ul>
  <li><a href="?22">National Coordinator</a></li>
  <li>
  <a href="?21">Administrator</a>
  <ul>
  <li><a href="?211">Invite new users</a></li>
  <li><a href="?211">New Account Requests</a></li>
  <li><a href="?212">Pending Invitations</a></li>
  </ul>
  </li>
  

  <li>
  <a href="?21">User Administrator</a>  
  <ul>
  <li><a href="?211">Invite new users</a></li>
  <li><a href="?211">New Account Requests</a></li>
  <li><a href="?212">Pending Invitations</a></li>
  </ul>
  </li>

  <li><a href="?13">FAQ</a></li>
 </ul>
 </li>*/

?>

