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

$A::

While GetKeyState("A","P"){
    Send, {A}
    Sleep, 200
    Send, {S}
    Sleep, 150
    Send, {D}
    Sleep, 150
    Send, {F}
    Sleep, 150
    Send, {G}
    Sleep, 150
    Send, {H}

}
return

$End::Suspend

$Del::ExitApp