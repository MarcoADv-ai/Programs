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

$F3::
While GetKeyState("F3","P"){
    Send, {F3}
    MouseClick, Left
    Sleep, 1
}
return


$+^F12::ExitApp