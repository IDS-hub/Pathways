using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;
using System;

public class SliderController : MonoBehaviour
{
    public event Action <int> OnPainIntensityChanged;
    public List<Sprite> PainIntesitySprites;
    public List<GameObject> PainBubbleSprites;

    public List<Text> Texts;
    [Range(0.1f, 1f)]
    public float SliderValue = 0;
    public Image SpriteImage;
    public Slider slider;

    public int PainIntensity
    {
        get { return _painIntensity; }
        set
        {
            if (OnPainIntensityChanged != null)
                OnPainIntensityChanged(value);

            _painIntensity = value;
        }
    }
    private int _painIntensity = 0;
    private float tmpSliderVal;

    // Use slider.OnValueChanged.AddListener() instead this.
    public void OnSliderChange(float val)
    {
        val = Mathf.Round(10 * val) - 1;
        foreach (var text in Texts)
        {
            text.fontSize = 36;
            text.fontStyle = FontStyle.Normal;
        }

        foreach (var bubble in PainBubbleSprites)
        {
            bubble.SetActive(false);
        }

        PainBubbleSprites[(int)val].SetActive(true);

        Texts[(int)val].fontStyle = FontStyle.Bold;
        Texts[(int)val].fontSize = 54;

        var v = SpriteImage.rectTransform.sizeDelta;
        v.y = 76 * val + 76;
        SpriteImage.rectTransform.sizeDelta = v;
        SpriteImage.sprite = PainIntesitySprites[(int)val];
        PainIntensity = (int)val + 1;
    }


   
    void Update()
    {
        
        if (tmpSliderVal != slider.value)
        {
            OnSliderChange(slider.value);
            tmpSliderVal = slider.value;
        }
    }
}
