if not A_IsAdmin
{
	Run *RunAs "%A_ScriptFullPath%"
	ExitApp
}

SendMode Input

CoordMode, Pixel, Screen
CoordMode, Mouse, Screen

;=========== SKILL DELAY CONFIG
dSkills = 100
dHealer = 100
dHeal = 210
dFinal = 1000
;==============================

Gui, Font, S8 CDefault, Consolas

;POEM
Gui, Add, Text, x12 y12 w37 h30, POEM
Gui, Add, CheckBox, vPoem x62 y9 w20 h20,
Gui, Add, DropDownList, vCS1 x92 y9 w40, F1|F2||F3|F4|F5|F6|F7|F8|F9
Gui, Add, Edit, vWID1 x142 y10 w100 h20 +ReadOnly,
Gui, Add, Button, gSet1 x252 y10 w40 h20 , Set

;SRVC4U
Gui, Add, Text, x12 y42 w37 h30, SRVC4U
Gui, Add, CheckBox, vService x62 y39 w20 h20,
Gui, Add, DropDownList, vGS1 x92 y39 w40, F1|F2||F3|F4|F5|F6|F7|F8|F9
Gui, Add, Edit, vWID2 x142 y40 w100 h20 +ReadOnly,
Gui, Add, Button, gSet2 x252 y40 w40 h20 , Set

;RIFF
Gui, Add, Text, x12 y72 w37 h30, RIFF
Gui, Add, CheckBox, vRiff x62 y69 w20 h20,
Gui, Add, DropDownList, vCS2 x92 y69 w40, F1|F2||F3|F4|F5|F6|F7|F8|F9
Gui, Add, Edit, vWID3 x142 y70 w100 h20 +ReadOnly,
Gui, Add, Button, gSet3 x252 y70 w40 h20 , Set

;APPLE
Gui, Add, Text, x12 y102 w37 h30, APPLE
Gui, Add, CheckBox, vApple x62 y99 w20 h20,
Gui, Add, DropDownList, vCS3 x92 y99 w40, F1|F2||F3|F4|F5|F6|F7|F8|F9
Gui, Add, Edit, vWID4 x142 y100 w100 h20 +ReadOnly,
Gui, Add, Button, gSet4 x252 y100 w40 h20 , Set

;GOSPEL
;Gui, Add, Text, x12 y132 w37 h30, GOSPEL
;Gui, Add, CheckBox, vGospel x62 y129 w20 h20,
;Gui, Add, DropDownList, vPS1 x92 y129 w40, F1||F2|F3|F4|F5|F6|F7|F8|F9
;Gui, Add, Edit, vWID5 x142 y130 w100 h20 +ReadOnly,
;Gui, Add, Button, gSet5 x252 y130 w40 h20 , Set

;BUTTONS
Gui, Add, Button, gStart x12 y170 w60 h20 , Start
Gui, Add, Button, gHealer x127 y170 w60 h20 , Healer
Gui, Add, Button, gClose x232 y170 w60 h20 , ExitApp

Gui, Show, xCenter yCenter h200 w304, louri songs
Return

Set1:
{
	KeyWait, Space, D
	MyWinID := WinExist("A")
	GuiControl,, WID1, %MyWinID%
	Gui, Show
	Return
}

Set2:
{
	KeyWait, Space, D
	MyWinID := WinExist("A")
	GuiControl,, WID2, %MyWinID%
	Gui, Show
	Return
}

Set3:
{
	KeyWait, Space, D
	MyWinID := WinExist("A")
	GuiControl,, WID3, %MyWinID%
	Gui, Show
	Return
}

Set4:
{
	KeyWait, Space, D
	MyWinID := WinExist("A")
	GuiControl,, WID4, %MyWinID%
	Gui, Show
	Return
}

Set5:
{
	KeyWait, Space, D
	MyWinID := WinExist("A")
	GuiControl,, WID5, %MyWinID%
	Gui, Show
	Return
}

Healer:
{
	Gui, Submit,
	KeyWait, Space, D
	MouseGetPos, Hx, Hy
	Gui, Show
	Return
}

Start:
{
	Loop,
	{
		If(Poem == 1)
		{
			WinActivate, ahk_id %WID1%
			Sleep, 100
			ControlSend,, {F3}, ahk_id %WID1%
			Sleep, 100
			ControlSend,, {F5}, ahk_id %WID1%
			Sleep, 800
			MouseMove, Hx, Hy
			Sleep, 50
			SendEvent {Click}
			Sleep, %dHealer%
			ControlSend,, {%CS1%}, ahk_id %WID1%
		}
		If(Service == 1)
		{
			WinActivate, ahk_id %WID2%
			Sleep, 100
			ControlSend,, {F3}, ahk_id %WID2%
			Sleep, 100
			ControlSend,, {F5}, ahk_id %WID2%
			Sleep, 800
			MouseMove, Hx, Hy
			Sleep, 50
			SendEvent {Click}
			Sleep, %dHealer%
			ControlSend,, {%GS1%}, ahk_id %WID2%
		}
		If(Riff == 1)
		{
			WinActivate, ahk_id %WID3%
			Sleep, 100
			ControlSend,, {F3}, ahk_id %WID3%
			Sleep, 100
			ControlSend,, {F5}, ahk_id %WID3%
			Sleep, 800
			MouseMove, Hx, Hy
			Sleep, 50
			SendEvent {Click}
			Sleep, %dHealer%
			ControlSend,, {%CS2%}, ahk_id %WID3%
		}
		If(Apple == 1)
		{
			WinActivate, ahk_id %WID4%
			Sleep, 100
			ControlSend,, {F3}, ahk_id %WID4%
			Sleep, 100
			ControlSend,, {F5}, ahk_id %WID4%
			Sleep, 800
			MouseMove, Hx, Hy
			Sleep, 50
			SendEvent {Click}
			Sleep, %dHealer%
			ControlSend,, {%CS3%}, ahk_id %WID4%
		}
		
		Sleep, 1000
		
		Loop, %dHeal%
		{
			If(Poem == 1)
			{
				ControlSend,, {%CS1%}, ahk_id %WID1%
			}
			If(Service == 1)
			{
				ControlSend,, {%GS1%}, ahk_id %WID2%
			}
			If(Riff == 1)
			{
				ControlSend,, {%CS2%}, ahk_id %WID3%
			}
			If(Apple == 1)
			{
				ControlSend,, {%CS3%}, ahk_id %WID4%
			}
			Sleep, %dFinal%
		}
	}
}
Close:
{
ExitApp
}
GuiClose:
ExitApp
End::Pause, Toggle, 1