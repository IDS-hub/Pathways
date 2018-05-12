using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class UIUtilites : MonoBehaviour
{
    public static Color GetColorByPainIntensity (int intensity)
    {
        Color col = Color.black;
        switch (intensity)
        {
            case 0:
                col = Color.green;
                break;
            case 1:
            case 2:
            case 3:
                col = Constants.yellowColor;
                break;
            case 4:
            case 5:
            case 6:
            case 7:
                col = Constants.orangeColor;
                break;
            case 8:
            case 9:
            case 10:
                col = Constants.redColor;
                break;
            default:
                col = new Color(0f, 0f, 0f, 0f);
                break;
        }
        return col;
    }
	
	
}
