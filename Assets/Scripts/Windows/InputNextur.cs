using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;

public class InputNextur : MonoBehaviour {

    public static InputNextur Instance = null;
    public InputField[] inputFields;
    

    private void Awake()
    {
        if(Instance==null)
        {
            Instance = this;
        }
    }
    // Use this for initialization
    void Start () {
		
	}
	
	// Update is called once per frame
	void Update () {
		
	}

   
}
