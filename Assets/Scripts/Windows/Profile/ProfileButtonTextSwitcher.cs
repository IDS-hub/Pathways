using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.EventSystems;
using UnityEngine.UI;

public class ProfileButtonTextSwitcher : Button
{
    public Color PressedColor;
    public Color NormalColor;
    Text textField;

    protected override void Start()
    {
        base.Start();
        PressedColor = new Color(68f / 255f, 178f / 255f, 224f / 255f, 1f);
        NormalColor = new Color(80f / 255f, 80f / 255f, 80f / 255f, 1f);

        textField = GetComponentInChildren<Text>();
        ChangeTextLabelColor(false);
    }

    public override void OnPointerDown(PointerEventData eventData)
    {
        base.OnPointerDown(eventData);
        ChangeTextLabelColor(true);
    }

    public override void OnPointerUp(PointerEventData eventData)
    {
        base.OnPointerUp(eventData);
        ChangeTextLabelColor(false);
    }

    void ChangeTextLabelColor (bool IsPressed)
    {
        textField.color = IsPressed ? PressedColor : NormalColor;
	}
}
