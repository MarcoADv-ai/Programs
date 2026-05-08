#SingleInstance FORCE
#MaxHotkeysPerInterval 999999999999999999999
SetDefaultMouseSpeed, 0
SetKeyDelay -1,-1,-1

if not A_IsAdmin
{
   Run *RunAs "%A_ScriptFullPath%"
   ExitApp
}

$F3::
Send, {L}
While GetKeyState("F3","P"){
    Send, {F3}
    MouseClick, Left
    Sleep, 50
}
return


$End::Suspend

$Del::ExitApp