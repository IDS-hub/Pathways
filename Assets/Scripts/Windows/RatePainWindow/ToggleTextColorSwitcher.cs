using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;

[RequireComponent (typeof(Toggle))]
public class ToggleTextColorSwitcher : MonoBehaviour
{
    [SerializeField] Text toggleText;
    [SerializeField] Color pressedTextColor;
    [SerializeField] Color defaultTextColor;

    void Start ()
    {
        GetComponent<Toggle>().onValueChanged.AddListener(OnToggleValueChanged);
        toggleText = GetComponentInChildren<Text>();
    }
	
	void OnToggleValueChanged (bool value)
    {
        if (value)
            toggleText.color = pressedTextColor;
        else
            toggleText.color = defaultTextColor;
	}
}
