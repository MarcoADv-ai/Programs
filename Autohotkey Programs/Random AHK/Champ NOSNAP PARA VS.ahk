#SingleInstance FORCE
#MaxHotkeysPerInterval 999999999999999999999
SetDefaultMouseSpeed, 0
SetKeyDelay -1,-1,-1

if not A_IsAdmin
{
   Run *RunAs "%A_ScriptFullPath%"
   ExitApp
}

$F6::
Send, {L}
While GetKeyState("F6","P"){
    Send, {F6}
    MouseClick, Left
    Sleep, 1
}
return

$F4::
Send, {K}
Send, {F4}
Return



$F9::

While GetKeyState("F9","P"){
    Send, {F9}
    Sleep, 150
    Send, {7}
    Sleep, 150
    Send, {9}
    Sleep, 150
}
return



$End::Suspend

$Del::ExitApp