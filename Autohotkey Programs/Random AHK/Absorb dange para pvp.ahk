#SingleInstance FORCE
#MaxHotkeysPerInterval 999999999999999999999
SetDefaultMouseSpeed, 0
SetKeyDelay -1,-1,-1

if not A_IsAdmin
{
   Run *RunAs "%A_ScriptFullPath%"
   ExitApp
}

$F7::
Send, {K}
While GetKeyState("F7","P"){
    MouseMove, 2, 1, 0, R
    Send, {F7}
    MouseMove, -2, -1, 0, R
    Sleep, 20
    MouseMove, 2, 1, 0, R
    MouseClick, Left
    MouseMove, -2, -1, 0, R
    Sleep, 30
    MouseMove, 2, 1, 0, R
    Send, {F4}
    MouseMove, -2, -1, 0, R
    Sleep, 100
}
return

$F6::
Send, {L}
While GetKeyState("F6","P"){
    Send, {F6}
    MouseClick, Left
    Sleep, 1
}
return

$F2::
While GetKeyState("F2","P"){
    Send, {F2}
    Sleep, 40
}
return



$F4::
Send, {K}
Send, {F4}
Return