using System;
using UnityEngine;
using UnityEngine.EventSystems;
using UnityEngine.UI;

public class RotateButtonController : Button
{
    public event Action OnPointerDownEvent;
    public event Action OnPointerUpEvent;

    public override void OnPointerDown (PointerEventData eventData)
    {
        base.OnPointerEnter(eventData);
        if (OnPointerDownEvent != null)
            OnPointerDownEvent();
    }

    public override void OnPointerUp (PointerEventData eventData)
    {
        base.OnPointerUp(eventData);
        if (OnPointerUpEvent != null)
            OnPointerUpEvent();
    }


}
