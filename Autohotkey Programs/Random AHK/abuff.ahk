if not A_IsAdmin
{
	Run *RunAs "%A_ScriptFullPath%"
	ExitApp
}

;=========== SKILL DELAY CONFIG
dSkills = 130
dHealer = 150
dHeal = 80
dFinal = 300
;==============================

Gui, Font, S8 CDefault, Consolas

;Priest GUI
Gui, Add, GroupBox, x12 y9 w170 h170 , Priest Config
Gui, Add, Text, x22 y31 w50 h20, WinID:
Gui, Add, Edit, vWID1 x62 y29 w60 h20 +ReadOnly,
Gui, Add, Button, gSet1 x127 y29 w40 h20 , Set
Gui, Add, GroupBox, x22 y59 w140 h110 , Skills:
;Gui, Add, Text, x32 y82 w80 h20 , Recovery:
;Gui, Add, DropDownList, vHPS1 x102 y79 w40, F1|F2|F3|F4|F5||F6|F7|F8|F9
Gui, Add, Text, x32 y112 w80 h20 , Assumptio:
Gui, Add, DropDownList, vHPS2 x102 y109 w40, F1|F2|F3|F4||F5|F6|F7|F8|F9
Gui, Add, Text, x32 y142 w80 h20 , Impositio:
Gui, Add, DropDownList, vHPS3 x102 y139 w40, F1|F2|F3|F4|F5|F6|F7|F8||F9

;Soul Linker GUI
Gui, Add, GroupBox, x12 y189 w170 h170 , Soul Linker Config
Gui, Add, Text, x22 y211 w50 h20, WinID:
Gui, Add, Edit, vWID2 x62 y209 w60 h20 +ReadOnly,
Gui, Add, Button, gSet2 x127 y209 w40 h20 , Set
Gui, Add, GroupBox, x22 y239 w140 h110 , Skills:
Gui, Add, Text, x32 y262 w80 h20 , Kaizel:
Gui, Add, DropDownList, vLS1 x102 y259 w40, F1||F2|F3|F4|F5|F6|F7|F8|F9
Gui, Add, Text, x32 y292 w80 h20, Kaupe:
Gui, Add, DropDownList, vLS2 x102 y289 w40, F1|F2||F3|F4|F5|F6|F7|F8|F9

;Paladin GUI
Gui, Add, GroupBox, x194 y9 w170 h170 , Paladin Config
Gui, Add, Text, x204 y31 w50 h20, WinID:
Gui, Add, Edit, vWID3 x241 y29 w60 h20 +ReadOnly,
Gui, Add, Button, gSet3 x306 y29 w40 h20 , Set
Gui, Add, GroupBox, x204 y59 w140 h110 , Skills:
Gui, Add, Text, x214 y82 w80 h20 , Providence:
Gui, Add, DropDownList, vPS1 x284 y79 w40, F1|F2|F3||F4|F5|F6|F7|F8|F9
;Provoke Enabled
Gui, Add, Text, x214 y112 w80 h20 , Provoke:
Gui, Add, DropDownList, vPS2 x284 y109 w40, F1|F2||F3|F4|F5|F6|F7|F8|F9

Gui, Add, Button, gHealer x194 y199 w60 h20 , Healer
Gui, Add, Button, gStart x194 y344 w60 h20 , Start
Gui, Add, Button, gInject x304 y199 w60 h20 , Inject
Gui, Add, Button, gClose x304 y344 w60 h20 , ExitApp

Gui, Show, x525 y287 h376 w376, RO AutoBuffs
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

Healer:
{
	Gui, Submit, NoHide
	KeyWait, Space, D
	MouseGetPos, Hx, Hy
	Gui, Show
	Return
}

Inject:
{
	Gui, Submit, NoHide
	KeyWait, Space, D
	MouseGetPos, Cx, Cy
	Gui, Show
	Return
}

Start:
{
	Loop,
	{
		WinActivate, ahk_id %WID3%
		Sleep, 100
		MouseClick, Left, Hx, Hy
		Sleep, %dHealer%
			
		WinActivate, ahk_id %WID2%
		Sleep, 100
		MouseClick, Left, Hx, Hy
		Sleep, %dHealer%
			
		WinActivate, ahk_id %WID1%
		Sleep, 100
		MouseClick, Left, Hx, Hy
		Sleep, %dHealer%
		
		Loop, %dHeal%
		{

			;Linker window
			WinActivate, ahk_id %WID2%
			Sleep, 50
			
			;Kaizel
			ControlSend,, {%LS1%}, ahk_id %WID2%
			Sleep, 50
			MouseClick, Left, Cx, Cy
			Sleep, %dSkills%
			
			;-----------------------------
			
			;Priest window
			WinActivate, ahk_id %WID1%
			Sleep, 50
			
			;Assumptio
			ControlSend,, {%HPS2%}, ahk_id %WID1%
			Sleep, 50
			MouseClick, Left, Cx, Cy
			Sleep, %dSkills%
			
			;-----------------------------
			
			;Paladin window
			WinActivate, ahk_id %WID3%
			Sleep, 50
			
			;Providence
			ControlSend,, {%PS1%}, ahk_id %WID3%
			Sleep, 50
			MouseClick, Left, Cx, Cy
			Sleep, %dSkills%
			
			;-----------------------------
			
			;Linker window
			WinActivate, ahk_id %WID2%
			Sleep, 50
			
			;Kaupe
			ControlSend,, {%LS2%}, ahk_id %WID2%
			Sleep, 50
			MouseClick, Left, Cx, Cy
			Sleep, %dSkills%
			
			;-----------------------------
			
			;Priest window
			WinActivate, ahk_id %WID1%
			Sleep, 50
			
			;Impositio
			ControlSend,, {%HPS3%}, ahk_id %WID1%
			Sleep, 50
			MouseClick, Left, Cx, Cy
			Sleep, %dSkills%
			;-----------------------------
			
			;Paladin window
			WinActivate, ahk_id %WID3%
			Sleep, 50
			
			;Providence
			ControlSend,, {%PS1%}, ahk_id %WID3%
			Sleep, 50
			MouseClick, Left, Cx, Cy
			Sleep, %dSkills%
			

		}
		
		Sleep, %dFinal%
	}
}

Close:
{
	ExitApp
}
GuiClose:
ExitApp
-::Pause, Toggle, 1