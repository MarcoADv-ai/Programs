#NoEnv
#SingleInstance Force
SendMode, Input
SetBatchLines, -1
SetKeyDelay, -1, -1
SetControlDelay, -1

; Variables globales
Global PIDList := []
Global VersionList := ["RoV"]

; Dirección de memoria del nombre del personaje
Global Char_RoV := 0x010DF5D8

; Función para obtener la lista de ventanas de cliente
ClientList()
{
    Global PIDList, Char_RoV
    GuiControl,, DDLClient, | ; Limpiar lista desplegable
    WinGet, WinList, List
    pIndex := 1
    
    Loop, %WinList%
    {
        WinGet, PID0, PID, % "ahk_id" WinList%A_Index%
        WinGetTitle, wTitle, ahk_pid %PID0%
        
        ; Filtrar solo ventanas del juego (modificar según el nombre de tu ventana)
        If !InStr(wTitle, "HoneyRo ~") 
            Continue

        ; Leer el nombre del personaje de la memoria
        CharName := ReadMemory(PID0, Char_RoV, 32) ; Se asume que el nombre del personaje tiene un máximo de 32 caracteres

        ; Agregar el nombre del personaje y "HoneyRo" a la lista
        PIDList[pIndex] := PID0
        Element := "HoneyRo [" CharName "]" ; Cambiar a "HoneyRo [Nombre del Personaje]"
        GuiControl,, DDLClient, %Element%
        pIndex++
    }
}

; Función para leer memoria
ReadMemory(PID, Address, Size)
{
    VarSetCapacity(Buffer, Size) ; Crear un buffer para almacenar los datos leídos
    hProcess := DllCall("OpenProcess", "UInt", 0x10 | 0x400, "UInt", 0, "UInt", PID, "UInt") ; 0x10 es PROCESS_VM_READ y 0x400 es PROCESS_QUERY_INFORMATION
    DllCall("ReadProcessMemory", "UInt", hProcess, "UInt", Address, "UInt", &Buffer, "UInt", Size, "UInt") ; Leer memoria
    DllCall("CloseHandle", "UInt", hProcess) ; Cerrar el handle del proceso
    return StrGet(&Buffer, "UTF-8") ; Convertir el buffer a string, asumiendo que está en UTF-8
}

; Función que se ejecuta al seleccionar una ventana del DDLClient
DDLClient_Select()
{
    Global PIDList
    Gui, Submit, NoHide
    PID := PIDList[DDLClient] ; Obtener PID de la ventana seleccionada
    MsgBox, Ventana seleccionada con PID: %PID%
}

; Crear la interfaz gráfica (GUI)
Gui, Font, s10, Segoe UI
Gui, Add, GroupBox, xm ym w220 h60, Test
Gui, Add, DDL, xp+10 yp+20 r5 w200 vDDLClient gDDLClient_Select, 
ClientList()

; Agregar botones
Gui, Add, Button, xm+20 yp+70 w80 h30 gIniciar, Iniciar
Gui, Add, Button, xp+120 yp+0 w80 h30 gButtonCerrar, Cerrar ; Mover "Cerrar" aún más a la derecha

Gui, Show, w240 h120, Test
Return

; Función del botón "Iniciar"
Iniciar:
    ; Aquí puedes agregar la lógica que deseas ejecutar al presionar "Iniciar"
    MsgBox, Iniciar botón presionado.
Return

; Cerrar la ventana
ButtonCerrar:
GuiClose:
ExitApp
